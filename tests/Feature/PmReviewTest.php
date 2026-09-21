<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
        $this->actingAs($this->manager)->get(route('requests.index', ['status' => 'pm_review']))->assertOk()->assertSee($request->reference_no)->assertSee('Assign');
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
        $this->assertSame(0, $this->dialA->notifications()->count());
        $this->postJson(route('requests.assignment.proceed', $request), ['current_password' => 'ManagerPass!'])->assertOk();
        $this->assertSame(1, $this->dialA->notifications()->count());
        $this->actingAs($this->dealer)->get(route('requests.show', $request))->assertOk()->assertSee('Exposed wiring')->assertSee('Reviewed by');
        $this->actingAs($this->dialA)->get(route('requests.show', $request))->assertOk()->assertSee('Set Inspection');
        $this->actingAs($this->pmAdmin)->patchJson(route('requests.assignment.update', $request), $this->decision([
            'review_pending' => 1, 'current_password' => 'AdminPass!', 'assignment_type' => 'in_house',
        ]))->assertUnprocessable()->assertJsonValidationErrors('assignment');
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

    public function test_each_action_requires_password_even_after_prior_verification(): void
    {
        $request = $this->submitRequest();
        $this->actingAs($this->manager)->patchJson(route('requests.assignment.update', $request), $this->decision([
            'assignment_type' => 'in_house', 'remember_confirmation' => 1,
        ]))->assertOk();
        $this->assertNull(session('pm_action_confirmation'));
        $this->postJson(route('requests.assignment.proceed', $request), ['quick_confirm' => 1])
            ->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->assertFalse($request->fresh()->hasProceeded());
        $this->postJson(route('requests.assignment.proceed', $request), ['current_password' => 'ManagerPass!'])->assertOk();
        $this->patchJson(route('requests.in-house.work-order', $request), [
            'in_house_work_order' => 'Repair the showroom circuit.', 'quick_confirm' => 1,
        ])->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->assertNull($request->fresh()->in_house_work_order);
        $this->patchJson(route('requests.in-house.work-order', $request), [
            'in_house_work_order' => 'Repair the showroom circuit.', 'current_password' => 'ManagerPass!',
        ])->assertOk();
        $this->assertSame('Repair the showroom circuit.', $request->fresh()->in_house_work_order);
    }

    public function test_existing_quick_tap_sessions_cannot_bypass_password_for_either_pm_role(): void
    {
        foreach ([[$this->manager, 'ManagerPass!'], [$this->pmAdmin, 'AdminPass!']] as [$pm, $password]) {
            $request = $this->submitRequest();
            $this->actingAs($pm)->withSession(['pm_action_confirmation' => [
                'user_id' => $pm->id,
                'fingerprint' => hash('sha256', $pm->getAuthPassword().$pm->role),
                'expires_at' => now()->addHours(12)->timestamp,
            ]])->patchJson(route('requests.assignment.update', $request), $this->decision([
                'current_password' => null, 'quick_confirm' => 1,
            ]))->assertUnprocessable()->assertJsonValidationErrors('current_password');
            $this->assertNull(session('pm_action_confirmation'));
            $this->assertTrue($request->fresh()->isAwaitingPmReview());
            $this->patchJson(route('requests.assignment.update', $request), $this->decision([
                'current_password' => 'wrong', 'quick_confirm' => 1,
            ]))->assertUnprocessable()->assertJsonValidationErrors('current_password');
            $this->patchJson(route('requests.assignment.update', $request), $this->decision([
                'current_password' => $password,
            ]))->assertOk();
        }
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
        $response->assertSee('pmConfirmationDialog')->assertSee('id="pmConfirmationPassword" name="current_password" type="password" autocomplete="current-password" required', false)->assertDontSee('Quick Tap')->assertDontSee('data-quick-confirm', false)->assertSee('data-pm-confirm-form', false)->assertDontSee('id="assignment_password"', false);
        preg_match('/<section id="request-assignment".*?<\/section>/s', $response->getContent(), $panel);
        $this->assertNotEmpty($panel);
        $this->assertStringNotContainsString('type="password"', $panel[0]);
    }

    public function test_assign_locks_fields_and_only_undo_assigned_reopens_them_before_proceed(): void
    {
        $request = $this->submitRequest();
        $this->actingAs($this->manager)->get(route('requests.show', $request))->assertOk()->assertSee('>Assign</button>', false)->assertDontSee('Undo Assigned');
        $this->patchJson(route('requests.assignment.update', $request), $this->decision())->assertOk();
        $this->assertTrue($request->fresh()->isAssignmentStaged());
        $page = $this->get(route('requests.show', $request))->assertOk()->assertSee('>Proceed</button>', false)->assertSee('>Undo Assigned</button>', false);
        $page->assertDontSee('id="review_remarks"', false)->assertDontSee('id="assignment_type"', false)->assertDontSee('id="in_house_work_order"', false);
        $this->patchJson(route('requests.assignment.update', $request), $this->decision(['priority' => 'regular']))->assertUnprocessable();
        $this->patchJson(route('requests.priority', $request), $this->decision(['priority' => 'regular']))->assertUnprocessable();
        $this->postJson(route('requests.assignment.undo', $request), ['current_password' => 'wrong'])->assertUnprocessable();
        $this->assertTrue($request->fresh()->isAssignmentStaged());
        $this->postJson(route('requests.assignment.undo', $request), ['current_password' => 'ManagerPass!'])->assertOk();
        $this->assertTrue($request->fresh()->isAwaitingPmReview());
        $this->actingAs($this->manager)->get(route('requests.show', $request))->assertOk()->assertSee('id="review_remarks"', false)->assertSee('>Assign</button>', false);
        $this->patchJson(route('requests.assignment.update', $request), $this->decision(['assignment_type' => 'in_house']))->assertOk();
        $this->get(route('requests.show', $request))->assertOk()->assertDontSee('id="in_house_work_order"', false)->assertSee('>Proceed</button>', false);
        $this->patchJson(route('requests.in-house.work-order', $request), ['in_house_work_order' => 'Prepare safe replacement wiring.', 'current_password' => 'ManagerPass!'])
            ->assertUnprocessable()->assertJsonValidationErrors('assignment');
        $this->assertNull($request->fresh()->in_house_work_order);
        $this->postJson(route('requests.assignment.undo', $request), ['current_password' => 'ManagerPass!'])->assertOk();
        $this->actingAs($this->manager)->patchJson(route('requests.in-house.work-order', $request), ['in_house_work_order' => 'Stale work', 'current_password' => 'ManagerPass!'])->assertUnprocessable();
    }

    public function test_proceeded_assignment_requires_administrator_undo_and_is_visible_in_admin_ui(): void
    {
        $request = $this->submitRequest();
        $administrator = User::factory()->create(['role' => 'admin', 'password' => 'SystemAdminPass!']);
        $this->actingAs($this->manager)->postJson(route('requests.assignment.proceed', $request), ['current_password' => 'ManagerPass!'])->assertUnprocessable();
        $this->patchJson(route('requests.assignment.update', $request), $this->decision())->assertOk();
        $this->actingAs($this->dialA)->get(route('requests.show', $request))->assertForbidden();
        $this->actingAs($this->manager)->postJson(route('requests.assignment.proceed', $request), ['current_password' => 'ManagerPass!', 'remember_confirmation' => 1])->assertOk();
        $this->assertTrue($request->fresh()->hasProceeded());
        $this->get(route('requests.show', $request))->assertOk()->assertDontSee('>Undo Assigned</button>', false)->assertDontSee('>Proceed</button>', false);
        foreach ([$this->manager, $this->pmAdmin, $this->dealer, $this->dialA] as $user) {
            $this->actingAs($user)->postJson(route('requests.assignment.undo', $request), ['quick_confirm' => 1, 'current_password' => 'SystemAdminPass!'])->assertForbidden();
        }
        $this->actingAs($administrator)->get(route('requests.show', $request))->assertOk()->assertSee('>Undo Assigned</button>', false)->assertSee('Administrator password');
        $this->postJson(route('requests.assignment.undo', $request), ['current_password' => 'ManagerPass!'])->assertUnprocessable();
        $this->assertTrue($request->fresh()->hasProceeded());
        $this->postJson(route('requests.assignment.undo', $request), ['current_password' => 'SystemAdminPass!'])->assertOk();
        $this->assertTrue($request->fresh()->isAwaitingPmReview());
        $this->actingAs($this->dialA)->patchJson(route('requests.inspection.date', $request), [])->assertForbidden();
        $this->get(route('requests.index'))->assertOk()->assertDontSee($request->reference_no);
        $this->actingAs($this->manager)->get(route('requests.show', $request))->assertOk()->assertSee('id="review_remarks"', false);
        $this->patchJson(route('requests.assignment.update', $request), $this->decision())->assertOk();
        $this->assertTrue($request->fresh()->isAssignmentStaged());
    }

    public function test_in_house_workflow_is_inaccessible_until_proceed_for_both_pm_roles(): void
    {
        $administrator = User::factory()->create(['role' => 'admin', 'password' => 'SystemAdminPass!']);
        foreach ([[$this->manager, 'ManagerPass!'], [$this->pmAdmin, 'AdminPass!']] as [$pm, $password]) {
            $request = $this->submitRequest();
            $decision = $this->decision(['assignment_type' => 'in_house', 'current_password' => $password]);
            $work = ['in_house_work_order' => 'Replace and test the damaged wiring.', 'current_password' => $password];
            $this->actingAs($pm)->patchJson(route('requests.assignment.update', $request), $decision)->assertOk();
            $this->get(route('requests.show', $request))->assertOk()
                ->assertSee('>Proceed</button>', false)
                ->assertDontSee('class="in-house-workflow"', false)
                ->assertDontSee('id="in_house_work_order"', false)
                ->assertDontSee('id="in_house_completion_files"', false);
            $this->patchJson(route('requests.in-house.work-order', $request), $work)
                ->assertUnprocessable()->assertJsonValidationErrors('assignment');
            $this->postJson(route('requests.in-house.completion', $request), [
                'completion_files' => [UploadedFile::fake()->create('report.pdf', 10, 'application/pdf')],
                'current_password' => $password,
            ])->assertUnprocessable()->assertJsonValidationErrors('assignment');
            $this->assertNull($request->fresh()->in_house_work_order);
            $this->assertNull($request->fresh()->in_house_work_order_at);
            $this->assertSame(0, $request->inHouseCompletionFiles()->count());

            $this->postJson(route('requests.assignment.proceed', $request), ['current_password' => $password])->assertOk();
            $this->get(route('requests.show', $request))->assertOk()->assertSee('id="in_house_work_order"', false);
            $this->patchJson(route('requests.in-house.work-order', $request), $work)->assertOk();
            $this->assertSame($work['in_house_work_order'], $request->fresh()->in_house_work_order);

            $this->actingAs($administrator)->postJson(route('requests.assignment.undo', $request), ['current_password' => 'SystemAdminPass!'])->assertOk();
            $this->actingAs($pm)->patchJson(route('requests.assignment.update', $request), $decision)->assertOk();
            $this->get(route('requests.show', $request))->assertOk()
                ->assertDontSee('id="in_house_work_order"', false)
                ->assertDontSee($work['in_house_work_order']);
            $this->patchJson(route('requests.in-house.work-order', $request), array_replace($work, ['in_house_work_order' => 'Premature change']))
                ->assertUnprocessable()->assertJsonValidationErrors('assignment');
            $this->assertSame($work['in_house_work_order'], $request->fresh()->in_house_work_order);
        }
    }
}
