<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PmReviewTest extends TestCase
{
    use RefreshDatabase;

    private User $dealer;
    private User $manager;
    private User $pmAdmin;
    private User $dialA;

    protected function setUp(): void
    {
        parent::setUp();
        config(['webpush.public_key' => null, 'webpush.private_key' => null]);
        $branch = Dealer::create(['source_no' => 1, 'name' => 'Review Dealer', 'city' => 'Pasig', 'area' => 'Metro Manila', 'brand' => 'Gateway']);
        $this->dealer = User::factory()->create(['role' => 'dealer', 'dealer_id' => $branch->id]);
        $this->manager = User::factory()->create(['role' => 'pm_manager', 'password' => 'ManagerPass!']);
        $this->pmAdmin = User::factory()->create(['role' => 'pm_admin', 'password' => 'AdminPass!']);
        $this->dialA = User::factory()->create(['role' => 'dial_a']);
    }

    private function submitRequest(): PropertyRequest
    {
        $this->actingAs($this->dealer)->post(route('requests.store'), [
            'name' => $this->dealer->name, 'designation' => 'Dealer', 'request_type' => 'Electrical Works',
            'description' => 'The showroom lights have failed.',
            'priority' => 'urgent', 'assignment_type' => 'dial_a', 'assigned_support_id' => $this->dialA->id,
        ])->assertSessionHasNoErrors()->assertRedirect();

        return PropertyRequest::latest('id')->firstOrFail();
    }

    private function decision(array $overrides = []): array
    {
        return array_replace([
            'priority' => 'urgent', 'remarks' => 'Exposed wiring is a safety risk; repair immediately.',
            'assignment_type' => 'dial_a', 'current_password' => 'ManagerPass!',
        ], $overrides);
    }

    public function test_dealer_submission_waits_for_pm_and_cannot_set_its_priority_or_assignment(): void
    {
        $request = $this->submitRequest();
        $this->assertTrue($request->isAwaitingPmReview());
        $this->assertNull($request->assigned_support_id);
        $this->assertNull($request->pm_reviewed_at);
        $this->assertSame('regular', $request->priority);
        $this->assertSame('Awaiting PM review', $request->priority_label);
        foreach ([$this->manager, $this->pmAdmin] as $pm) {
            $this->assertSame('new_request', $pm->notifications()->sole()->data['kind']);
        }
        $this->assertSame(0, $this->dialA->notifications()->count());
        $this->actingAs($this->dealer)->get(route('requests.show', $request))->assertOk()->assertSee('Awaiting PM Review')->assertDontSee('Set Inspection');
        $this->get(route('requests.create'))->assertOk()->assertDontSee('name="priority"', false);
        $this->actingAs($this->dialA)->get(route('requests.show', $request))->assertForbidden();
        $this->get(route('requests.index'))->assertOk()->assertDontSee($request->reference_no);
        $this->get(route('dashboard'))->assertOk()->assertDontSee($request->reference_no);
        $this->actingAs($this->manager)->get(route('requests.index', ['status' => 'pm_review']))->assertOk()->assertSee($request->reference_no)->assertSee('Review &amp; Assign', false);
    }

    public function test_priority_remarks_assignment_and_own_password_are_required_before_release(): void
    {
        $request = $this->submitRequest();
        $this->actingAs($this->manager);
        foreach (['priority', 'remarks', 'assignment_type'] as $field) {
            $this->patchJson(route('requests.assignment.update', $request), $this->decision([$field => '']))
                ->assertUnprocessable()->assertJsonValidationErrors($field);
        }
        $this->patchJson(route('requests.assignment.update', $request), $this->decision(['current_password' => 'AdminPass!']))
            ->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->assertTrue($request->fresh()->isAwaitingPmReview());
        $this->assertNull($request->fresh()->priority_remarks);
        $this->patchJson(route('requests.priority', $request), $this->decision())->assertUnprocessable();
    }

    public function test_review_records_pm_decision_notifies_dealer_and_releases_to_dial_a(): void
    {
        $request = $this->submitRequest();
        $this->actingAs($this->manager)->patchJson(route('requests.assignment.update', $request), $this->decision(['review_pending' => 1]))->assertOk()->assertJson(['success' => true]);
        $request->refresh();
        $this->assertSame('dial_a', $request->assignment_type);
        $this->assertSame($this->dialA->id, $request->assigned_support_id);
        $this->assertSame('urgent', $request->priority);
        $this->assertSame($this->manager->id, $request->pm_reviewed_by_id);
        $this->assertNotNull($request->pm_reviewed_at);
        $this->assertStringContainsString('Exposed wiring', $this->dealer->notifications()->sole()->data['message']);
        $this->assertSame(1, $this->dialA->notifications()->count());
        $this->actingAs($this->dealer)->get(route('requests.show', $request))->assertOk()->assertSee('Exposed wiring')->assertSee('Reviewed by');
        $this->actingAs($this->dialA)->get(route('requests.show', $request))->assertOk()->assertSee('Set Inspection');
        $this->actingAs($this->pmAdmin)->patchJson(route('requests.assignment.update', $request), $this->decision([
            'review_pending' => 1, 'current_password' => 'AdminPass!', 'assignment_type' => 'in_house',
        ]))->assertUnprocessable()->assertJsonValidationErrors('review');
        $this->assertSame('dial_a', $request->fresh()->assignment_type);
    }

    public function test_pm_admin_can_review_in_house_and_dealers_cannot_review(): void
    {
        $request = $this->submitRequest();
        foreach ([$this->dealer, $this->dialA] as $user) {
            $this->actingAs($user)->patchJson(route('requests.assignment.update', $request), $this->decision())->assertForbidden();
        }
        $this->actingAs($this->pmAdmin)->patchJson(route('requests.assignment.update', $request), $this->decision([
            'assignment_type' => 'in_house', 'current_password' => 'AdminPass!',
        ]))->assertOk();
        $request->refresh();
        $this->assertTrue($request->isInHouse());
        $this->assertSame($this->pmAdmin->id, $request->pm_reviewed_by_id);
        $this->assertSame($this->pmAdmin->id, $request->in_house_inspection_by_id);
        $this->assertSame(0, $this->dialA->notifications()->count());
    }

    public function test_one_click_confirmation_requires_prior_verification_and_remembers_no_password(): void
    {
        $request = $this->submitRequest();
        $this->actingAs($this->manager)->patchJson(route('requests.assignment.update', $request), $this->decision([
            'current_password' => null, 'quick_confirm' => 1,
        ]))->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->patchJson(route('requests.assignment.update', $request), $this->decision([
            'assignment_type' => 'in_house', 'remember_confirmation' => 1,
        ]))->assertOk();
        $this->assertStringNotContainsString('ManagerPass!', json_encode(session('pm_action_confirmation')));
        $this->get(route('requests.show', $request))->assertOk()->assertSee('data-quick-confirm="1"', false);
        $this->patchJson(route('requests.in-house.work-order', $request), [
            'in_house_work_order' => 'Repair the showroom circuit.', 'quick_confirm' => 1,
        ])->assertOk();
        $this->assertSame('Repair the showroom circuit.', $request->fresh()->in_house_work_order);
    }

    public function test_quick_confirmation_cannot_cross_accounts_survive_password_changes_or_expiry(): void
    {
        $request = $this->submitRequest();
        $this->actingAs($this->manager)->patchJson(route('requests.assignment.update', $request), $this->decision([
            'assignment_type' => 'in_house', 'remember_confirmation' => 1,
        ]))->assertOk();
        $action = ['in_house_work_order' => 'Repair wiring', 'quick_confirm' => 1];
        $this->actingAs($this->pmAdmin)->patchJson(route('requests.in-house.work-order', $request), $action)->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->actingAs($this->manager);
        $this->travel(13)->hours();
        $this->patchJson(route('requests.in-house.work-order', $request), $action)->assertUnprocessable();
        $this->travelBack();
        $this->manager->update(['password' => 'ChangedPass!']);
        $this->patchJson(route('requests.in-house.work-order', $request), $action)->assertUnprocessable();
        $this->assertNull($request->fresh()->in_house_work_order);
    }

    public function test_logout_and_opt_out_require_password_again(): void
    {
        $request = $this->submitRequest();
        $this->actingAs($this->manager)->patchJson(route('requests.assignment.update', $request), $this->decision(['remember_confirmation' => 0]))->assertOk();
        $this->assertNull(session('pm_action_confirmation'));
        $this->patchJson(route('requests.assignment.update', $request), $this->decision(['remember_confirmation' => 1]))->assertOk();
        $this->assertNotNull(session('pm_action_confirmation'));
        $this->post(route('logout'))->assertRedirect();
        $this->assertNull(session('pm_action_confirmation'));
        $this->actingAs($this->manager)->patchJson(route('requests.assignment.update', $request), $this->decision(['current_password' => null, 'quick_confirm' => 1]))->assertUnprocessable();
    }

    public function test_all_dial_a_actions_are_blocked_while_review_is_pending(): void
    {
        $request = $this->submitRequest();
        $this->actingAs($this->dialA);
        foreach (['schedule', 'assign', 'status', 'inspection.date', 'inspection.complete', 'work-order.date'] as $route) {
            $this->patchJson(route('requests.'.$route, $request), [])->assertForbidden();
        }
        foreach (['work-order.complete', 'service-report.start', 'service-report.upload', 'service-report.complete', 'notify-lead', 'finish'] as $route) {
            $this->postJson(route('requests.'.$route, $request), [])->assertForbidden();
        }
        $this->assertTrue($request->fresh()->isAwaitingPmReview());
    }

    public function test_password_is_in_a_confirmation_dialog_not_in_the_assignment_form(): void
    {
        $request = $this->submitRequest();
        $response = $this->actingAs($this->manager)->get(route('requests.show', $request))->assertOk();
        $response->assertSee('pmConfirmationDialog')->assertSee('Enable one-click confirmation for this sign-in')->assertSee('data-pm-confirm-form', false)->assertDontSee('id="assignment_password"', false);
        preg_match('/<section id="request-assignment".*?<\/section>/s', $response->getContent(), $panel);
        $this->assertNotEmpty($panel);
        $this->assertStringNotContainsString('type="password"', $panel[0]);
    }
}
