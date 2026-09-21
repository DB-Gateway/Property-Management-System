<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InHouseAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private User $manager;
    private User $pmAdmin;
    private User $dealer;
    private User $dialA;
    private PropertyRequest $propertyRequest;

    protected function setUp(): void
    {
        parent::setUp();

        config(['webpush.public_key' => null, 'webpush.private_key' => null]);
        Storage::fake('local');

        $branch = Dealer::create([
            'source_no' => 301,
            'name' => 'Gateway Motors',
            'city' => 'Pasig',
            'area' => 'Metro Manila',
            'brand' => 'Gateway',
        ]);
        $this->dealer = User::factory()->create([
            'role' => 'dealer', 'dealer_id' => $branch->id, 'password' => 'DealerPass2026!',
        ]);
        $this->manager = User::factory()->create([
            'role' => 'pm_manager', 'name' => 'Manager Maria', 'password' => 'ManagerPass2026!',
        ]);
        $this->pmAdmin = User::factory()->create([
            'role' => 'pm_admin', 'name' => 'Admin Alex', 'password' => 'PmAdminPass2026!',
        ]);
        $this->dialA = User::factory()->create([
            'role' => 'dial_a', 'password' => 'DialPass2026!',
        ]);

        $this->propertyRequest = PropertyRequest::create([
            'reference_no' => 'GPM-20260921-INHOUSE',
            'dealer_id' => $branch->id,
            'dealer_name' => $branch->name,
            'submitted_by' => $this->dealer->id,
            'submitter_name' => $this->dealer->name,
            'designation' => 'Branch Staff',
            'branch' => $branch->city,
            'area' => $branch->area,
            'request_type' => 'Electrical Works',
            'priority' => 'regular',
            'description' => 'Repair the showroom lighting.',
            'request_date' => today(),
            'due_date' => today()->addDays(4),
            'status' => 'pending',
        ])->refresh();
    }

    public function test_existing_request_defaults_to_dial_a_and_both_pm_roles_can_assign_in_house(): void
    {
        $this->assertSame('dial_a', $this->propertyRequest->assignment_type);
        $this->assertFalse($this->propertyRequest->isInHouse());

        $this->assignInHouse($this->manager, 'ManagerPass2026!');
        $this->assertSame('on_going', $this->propertyRequest->status);
        $this->assertNotNull($this->propertyRequest->in_house_requested_at);
        $this->assertStringContainsString('PM Manager', $this->propertyRequest->assignment_by_label);
        $this->assertStringContainsString('Manager Maria', $this->propertyRequest->assignment_by_label);
        $this->assertStringContainsString('PM Manager', $this->propertyRequest->in_house_inspection_by_label);

        $this->actingAs($this->pmAdmin)->patch(route('requests.assignment.update', $this->propertyRequest), [
            'assignment_type' => 'dial_a', 'current_password' => 'PmAdminPass2026!',
        ])->assertSessionHasNoErrors()->assertRedirect();
        $this->assignInHouse($this->pmAdmin, 'PmAdminPass2026!');
        $this->assertStringContainsString('PM Admin', $this->propertyRequest->assignment_by_label);
        $this->assertStringContainsString('Admin Alex', $this->propertyRequest->assignment_by_label);
        $this->assertStringContainsString('PM Admin', $this->propertyRequest->in_house_inspection_by_label);
    }

    public function test_assignment_requires_the_signed_in_accounts_password_and_only_two_values_are_valid(): void
    {
        foreach ([null, 'WrongPassword!', 'PmAdminPass2026!'] as $password) {
            $this->actingAs($this->manager)->patchJson(route('requests.assignment.update', $this->propertyRequest), [
                'assignment_type' => 'in_house', 'current_password' => $password,
            ])->assertUnprocessable()->assertJsonValidationErrors('current_password');
            $this->assertSame('dial_a', $this->propertyRequest->refresh()->assignment_type);
        }

        $this->actingAs($this->manager)->patchJson(route('requests.assignment.update', $this->propertyRequest), [
            'assignment_type' => 'other_contractor', 'current_password' => 'ManagerPass2026!',
        ])->assertUnprocessable()->assertJsonValidationErrors('assignment_type');
        $this->assertNull($this->propertyRequest->refresh()->in_house_requested_at);
    }

    public function test_care_of_is_taken_from_the_actor_and_survives_account_changes(): void
    {
        $this->actingAs($this->manager)->patch(route('requests.assignment.update', $this->propertyRequest), [
            'assignment_type' => 'in_house',
            'current_password' => 'ManagerPass2026!',
            'assignment_by_id' => $this->pmAdmin->id,
            'assignment_by_name' => 'Forged actor',
            'assignment_by_role' => 'pm_admin',
            'in_house_inspection_by_id' => $this->pmAdmin->id,
            'in_house_inspection_by_name' => 'Forged actor',
        ])->assertSessionHasNoErrors()->assertRedirect();

        $this->propertyRequest->refresh();
        $assignmentLabel = $this->propertyRequest->assignment_by_label;
        $inspectionLabel = $this->propertyRequest->in_house_inspection_by_label;
        $this->assertStringContainsString('Manager Maria', $assignmentLabel);
        $this->assertStringContainsString('PM Manager', $inspectionLabel);
        $this->assertStringNotContainsString('Forged actor', $assignmentLabel);

        $this->manager->update(['name' => 'Renamed account', 'role' => 'pm_admin']);
        $this->assertSame($assignmentLabel, $this->propertyRequest->fresh()->assignment_by_label);
        $this->assertSame($inspectionLabel, $this->propertyRequest->fresh()->in_house_inspection_by_label);
    }

    public function test_non_pm_users_cannot_change_assignment_or_in_house_work(): void
    {
        $this->assignInHouse($this->manager, 'ManagerPass2026!');
        $administrator = User::factory()->create(['role' => 'admin', 'password' => 'AdminPass2026!']);

        foreach ([$this->dealer, $this->dialA, $administrator] as $user) {
            $this->actingAs($user)->patchJson(route('requests.assignment.update', $this->propertyRequest), [
                'assignment_type' => 'dial_a', 'current_password' => 'password',
            ])->assertForbidden();
            $this->actingAs($user)->patchJson(route('requests.in-house.work-order', $this->propertyRequest), [
                'in_house_work_order' => 'Unauthorized work', 'current_password' => 'password',
            ])->assertForbidden();
            $this->actingAs($user)->postJson(route('requests.in-house.completion', $this->propertyRequest), [
                'current_password' => 'password',
            ])->assertForbidden();
        }

        $this->assertTrue($this->propertyRequest->refresh()->isInHouse());
        $this->assertNull($this->propertyRequest->in_house_work_order);
        $this->assertNull($this->propertyRequest->completed_at);
    }

    public function test_work_order_and_completion_track_the_respective_pm_accounts_and_complete_request(): void
    {
        $this->assignInHouse($this->manager, 'ManagerPass2026!');
        $this->saveWorkOrder($this->pmAdmin, 'PmAdminPass2026!', 'Replace two lighting fixtures and test the circuit.');

        $this->assertSame('Replace two lighting fixtures and test the circuit.', $this->propertyRequest->in_house_work_order);
        $this->assertNotNull($this->propertyRequest->in_house_work_order_at);
        $this->assertStringContainsString('PM Admin', $this->propertyRequest->in_house_work_order_by_label);
        $this->assertStringContainsString('Admin Alex', $this->propertyRequest->in_house_work_order_by_label);
        $this->assertNull($this->propertyRequest->completed_at);

        $this->actingAs($this->manager)->post(route('requests.in-house.completion', $this->propertyRequest), [
            'completion_files' => [UploadedFile::fake()->create('completion-report.pdf', 50, 'application/pdf')],
            'current_password' => 'ManagerPass2026!',
            'in_house_completion_by_name' => 'Forged actor',
        ])->assertSessionHasNoErrors()->assertRedirect();

        $this->propertyRequest->refresh();
        $this->assertSame('completed', $this->propertyRequest->status);
        $this->assertNotNull($this->propertyRequest->completed_at);
        $this->assertNotNull($this->propertyRequest->in_house_completed_at);
        $this->assertStringContainsString('PM Manager', $this->propertyRequest->in_house_completion_by_label);
        $this->assertStringContainsString('Manager Maria', $this->propertyRequest->in_house_completion_by_label);
        $this->assertFalse($this->propertyRequest->isAwaitingDialACompletion());
        $attachment = $this->propertyRequest->inHouseCompletionFiles()->sole();
        $this->assertSame('in_house_completion', $attachment->category);
        $this->assertSame($this->manager->id, $attachment->uploaded_by_id);
        $this->assertStringContainsString('PM Manager', $attachment->uploader_label);
        $this->assertStringContainsString('Manager Maria', $attachment->uploader_label);
        Storage::disk('local')->assertExists($attachment->path);
        $this->assertSame(0, $this->propertyRequest->serviceReportFiles()->count());

        $uploaderLabel = $attachment->uploader_label;
        $this->manager->update(['name' => 'Renamed PM account', 'role' => 'pm_admin']);
        $this->assertSame($uploaderLabel, $attachment->fresh()->uploader_label);
    }

    public function test_work_order_and_completion_reject_another_pm_users_password_without_changes(): void
    {
        $this->assignInHouse($this->manager, 'ManagerPass2026!');
        $this->actingAs($this->pmAdmin)->patchJson(route('requests.in-house.work-order', $this->propertyRequest), [
            'in_house_work_order' => 'Replace fixtures', 'current_password' => 'ManagerPass2026!',
        ])->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->assertNull($this->propertyRequest->refresh()->in_house_work_order);

        $this->saveWorkOrder($this->pmAdmin, 'PmAdminPass2026!');
        $this->actingAs($this->pmAdmin)->postJson(route('requests.in-house.completion', $this->propertyRequest), [
            'completion_files' => [UploadedFile::fake()->create('completion-report.pdf', 50, 'application/pdf')],
            'current_password' => 'ManagerPass2026!',
        ])->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->assertNull($this->propertyRequest->refresh()->completed_at);
        $this->assertSame(0, $this->propertyRequest->inHouseCompletionFiles()->count());
        $this->assertSame([], Storage::disk('local')->allFiles());
    }

    public function test_completion_requires_a_work_order_and_valid_report_attachment(): void
    {
        $this->assignInHouse($this->manager, 'ManagerPass2026!');
        $this->actingAs($this->manager)->postJson(route('requests.in-house.completion', $this->propertyRequest), [
            'completion_files' => [UploadedFile::fake()->create('completion-report.pdf', 50, 'application/pdf')],
            'current_password' => 'ManagerPass2026!',
        ])->assertUnprocessable();
        $this->assertNull($this->propertyRequest->refresh()->completed_at);
        $this->assertSame(0, $this->propertyRequest->inHouseCompletionFiles()->count());

        $this->actingAs($this->manager)->patchJson(route('requests.in-house.work-order', $this->propertyRequest), [
            'in_house_work_order' => '   ', 'current_password' => 'ManagerPass2026!',
        ])->assertUnprocessable()->assertJsonValidationErrors('in_house_work_order');

        $this->saveWorkOrder($this->manager, 'ManagerPass2026!');
        $this->actingAs($this->manager)->postJson(route('requests.in-house.completion', $this->propertyRequest), [
            'current_password' => 'ManagerPass2026!',
        ])->assertUnprocessable()->assertJsonValidationErrors('completion_files');
        $this->actingAs($this->manager)->postJson(route('requests.in-house.completion', $this->propertyRequest), [
            'completion_files' => [UploadedFile::fake()->create('malware.exe', 10, 'application/x-msdownload')],
            'current_password' => 'ManagerPass2026!',
        ])->assertUnprocessable()->assertJsonValidationErrors('completion_files.0');
        $disguisedFile = UploadedFile::fake()->createWithContent('disguised-report.pdf', '<?php echo "not a report";');
        // Use a real UploadedFile so MIME detection reads the contents; Laravel's fake guesses by extension.
        $actualUpload = new UploadedFile($disguisedFile->getPathname(), 'disguised-report.pdf', 'application/pdf', null, true);
        $this->actingAs($this->manager)->postJson(route('requests.in-house.completion', $this->propertyRequest), [
            'completion_files' => [$actualUpload],
            'current_password' => 'ManagerPass2026!',
        ])->assertUnprocessable()->assertJsonValidationErrors('completion_files.0');
        $this->assertNull($this->propertyRequest->refresh()->completed_at);
        $this->assertSame(0, $this->propertyRequest->inHouseCompletionFiles()->count());
    }

    public function test_reassignment_to_dial_a_preserves_original_workflow_and_blocks_in_house_updates(): void
    {
        $this->propertyRequest->update([
            'assigned_support_id' => $this->dialA->id,
            'status' => 'on_going',
            'inspection_date' => today(),
            'inspection_completed_at' => now()->subDay(),
            'representative_1' => 'Original representative',
        ]);
        $originalInspection = $this->propertyRequest->inspection_completed_at->toDateTimeString();
        $this->assignInHouse($this->manager, 'ManagerPass2026!');
        $this->saveWorkOrder($this->manager, 'ManagerPass2026!', 'Internal draft work order');

        $this->actingAs($this->pmAdmin)->patchJson(route('requests.assignment.update', $this->propertyRequest), [
            'assignment_type' => 'dial_a', 'current_password' => 'ManagerPass2026!',
        ])->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->assertTrue($this->propertyRequest->refresh()->isInHouse());

        $this->actingAs($this->pmAdmin)->patch(route('requests.assignment.update', $this->propertyRequest), [
            'assignment_type' => 'dial_a', 'current_password' => 'PmAdminPass2026!',
        ])->assertSessionHasNoErrors()->assertRedirect();

        $this->propertyRequest->refresh();
        $this->assertFalse($this->propertyRequest->isInHouse());
        $this->assertSame($this->dialA->id, $this->propertyRequest->assigned_support_id);
        $this->assertSame($originalInspection, $this->propertyRequest->inspection_completed_at->toDateTimeString());
        $this->assertSame('Original representative', $this->propertyRequest->representative_1);
        $this->actingAs($this->manager)->patchJson(route('requests.in-house.work-order', $this->propertyRequest), [
            'in_house_work_order' => 'Stale update', 'current_password' => 'ManagerPass2026!',
        ])->assertUnprocessable();
        $this->actingAs($this->manager)->postJson(route('requests.in-house.completion', $this->propertyRequest), [
            'completion_files' => [UploadedFile::fake()->create('stale-report.pdf', 50, 'application/pdf')],
            'current_password' => 'ManagerPass2026!',
        ])->assertUnprocessable();

        $this->actingAs($this->dealer)->get(route('requests.show', $this->propertyRequest))
            ->assertOk()->assertDontSee('Internal draft work order');
        $this->actingAs($this->dialA)->patch(route('requests.schedule', $this->propertyRequest), [
            'work_order_start_date' => today()->addDay()->format('Y-m-d'),
        ])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame(today()->addDay()->format('Y-m-d'), $this->propertyRequest->fresh()->work_order_start_date->format('Y-m-d'));
    }

    public function test_dial_a_cannot_mutate_any_legacy_workflow_stage_while_request_is_in_house(): void
    {
        $this->assignInHouse($this->manager, 'ManagerPass2026!');
        $routes = [
            ['patchJson', 'requests.schedule'],
            ['patchJson', 'requests.assign'],
            ['patchJson', 'requests.status'],
            ['patchJson', 'requests.inspection.date'],
            ['patchJson', 'requests.inspection.complete'],
            ['patchJson', 'requests.work-order.date'],
            ['postJson', 'requests.work-order.complete'],
            ['postJson', 'requests.service-report.start'],
            ['postJson', 'requests.service-report.upload'],
            ['postJson', 'requests.service-report.complete'],
            ['postJson', 'requests.notify-lead'],
            ['postJson', 'requests.finish'],
        ];
        foreach ($routes as [$method, $name]) {
            $this->actingAs($this->dialA)->{$method}(route($name, $this->propertyRequest), [])->assertForbidden();
        }
        $this->actingAs($this->dialA)->patchJson(route('requests.workflow.undo', [$this->propertyRequest, 'inspection']), [])->assertForbidden();

        $this->propertyRequest->refresh();
        $this->assertTrue($this->propertyRequest->isInHouse());
        $this->assertNull($this->propertyRequest->inspection_completed_at);
        $this->assertNull($this->propertyRequest->work_order_completed_at);
        $this->assertNull($this->propertyRequest->completed_at);
    }

    public function test_completion_report_is_accessible_to_pm_users_and_own_dealer_but_not_another_dealer(): void
    {
        $this->assignInHouse($this->manager, 'ManagerPass2026!');
        $this->saveWorkOrder($this->pmAdmin, 'PmAdminPass2026!');
        $this->actingAs($this->pmAdmin)->post(route('requests.in-house.completion', $this->propertyRequest), [
            'completion_files' => [UploadedFile::fake()->create('completion-report.pdf', 50, 'application/pdf')],
            'current_password' => 'PmAdminPass2026!',
        ])->assertSessionHasNoErrors()->assertRedirect();
        $attachment = $this->propertyRequest->inHouseCompletionFiles()->sole();

        foreach ([$this->manager, $this->pmAdmin, $this->dealer] as $user) {
            $this->actingAs($user)->get(route('attachments.show', $attachment))
                ->assertOk()->assertDownload('completion-report.pdf');
        }

        $otherBranch = Dealer::create([
            'source_no' => 302, 'name' => 'Other Dealer', 'city' => 'Cebu', 'area' => 'Visayas', 'brand' => 'Gateway',
        ]);
        $otherDealer = User::factory()->create(['role' => 'dealer', 'dealer_id' => $otherBranch->id]);
        $this->actingAs($otherDealer)->get(route('attachments.show', $attachment))->assertForbidden();
    }

    public function test_pm_assignment_panel_precedes_activity_and_offers_exactly_two_choices(): void
    {
        foreach ([$this->manager, $this->pmAdmin] as $user) {
            $response = $this->actingAs($user)->get(route('requests.show', $this->propertyRequest));
            $response->assertOk()->assertSee('name="assignment_type"', false);
            $html = $response->getContent();
            $this->assertLessThan(strpos($html, 'activity-overview-card'), strpos($html, 'name="assignment_type"'));

            $document = new DOMDocument;
            $previous = libxml_use_internal_errors(true);
            $document->loadHTML($html);
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
            $options = (new DOMXPath($document))->query('//select[@name="assignment_type"]/option');
            $this->assertCount(2, $options);
            $this->assertSame('dial_a', $options->item(0)->getAttribute('value'));
            $this->assertSame('Dial-A', trim($options->item(0)->textContent));
            $this->assertSame('in_house', $options->item(1)->getAttribute('value'));
            $this->assertSame('In house', trim($options->item(1)->textContent));
        }
    }

    public function test_dealer_sees_in_house_attribution_and_work_order_without_edit_controls(): void
    {
        $this->assignInHouse($this->manager, 'ManagerPass2026!');
        $this->saveWorkOrder($this->pmAdmin, 'PmAdminPass2026!', 'Replace damaged wiring and test all fixtures.');

        $this->actingAs($this->dealer)->get(route('requests.show', $this->propertyRequest))
            ->assertOk()
            ->assertSee('Inspection Request')
            ->assertSee('c/o')
            ->assertSee('PM Manager')
            ->assertSee('PM Admin')
            ->assertSee('Replace damaged wiring and test all fixtures.')
            ->assertDontSee('name="assignment_type"', false)
            ->assertDontSee('name="in_house_work_order"', false)
            ->assertDontSee('name="completion_files[]"', false);
    }

    public function test_completed_in_house_report_prints_care_of_and_attachments_and_hides_them_after_reassignment(): void
    {
        $this->propertyRequest->update([
            'status' => 'completed',
            'completed_at' => now()->subDay(),
            'service_report_completed_at' => now()->subDay(),
        ]);
        $this->assignInHouse($this->manager, 'ManagerPass2026!');
        $this->saveWorkOrder($this->pmAdmin, 'PmAdminPass2026!', 'Internal printed work order details');
        $this->actingAs($this->manager)->post(route('requests.in-house.completion', $this->propertyRequest), [
            'completion_files' => [
                UploadedFile::fake()->create('internal-completion-report.pdf', 50, 'application/pdf'),
                UploadedFile::fake()->createWithContent('internal-completion-photo.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==')),
            ],
            'current_password' => 'ManagerPass2026!',
        ])->assertSessionHasNoErrors()->assertRedirect();
        $this->propertyRequest->refresh();
        $photo = $this->propertyRequest->inHouseCompletionFiles()->where('original_name', 'internal-completion-photo.png')->firstOrFail();

        $this->actingAs($this->pmAdmin)->get(route('reports.print', ['search' => $this->propertyRequest->reference_no]))
            ->assertOk()
            ->assertSee($this->propertyRequest->reference_no)
            ->assertSee('Internal printed work order details')
            ->assertSee('c/o '.$this->propertyRequest->in_house_inspection_by_label)
            ->assertSee('c/o '.$this->propertyRequest->in_house_work_order_by_label)
            ->assertSee('c/o '.$this->propertyRequest->in_house_completion_by_label)
            ->assertSee('internal-completion-report.pdf')
            ->assertSee('internal-completion-photo.png')
            ->assertSee('src="'.route('attachments.show', $photo).'"', false);

        $this->actingAs($this->pmAdmin)->patch(route('requests.assignment.update', $this->propertyRequest), [
            'assignment_type' => 'dial_a', 'current_password' => 'PmAdminPass2026!',
        ])->assertSessionHasNoErrors()->assertRedirect();

        $this->actingAs($this->pmAdmin)->get(route('reports.print', ['search' => $this->propertyRequest->reference_no]))
            ->assertOk()
            ->assertSee($this->propertyRequest->reference_no)
            ->assertDontSee('Internal printed work order details')
            ->assertDontSee('internal-completion-report.pdf')
            ->assertDontSee('internal-completion-photo.png');
    }

    public function test_dial_a_and_administrator_see_no_legacy_workflow_forms_for_in_house_requests(): void
    {
        $this->assignInHouse($this->manager, 'ManagerPass2026!');
        $administrator = User::factory()->create(['role' => 'admin']);

        foreach ([$this->dialA, $administrator] as $viewer) {
            $response = $this->actingAs($viewer)->get(route('requests.show', $this->propertyRequest));
            $response->assertOk()->assertSee('Inspection Request')->assertSee('Manager Maria');
            foreach ([
                'requests.inspection.date', 'requests.inspection.complete',
                'requests.work-order.date', 'requests.work-order.complete',
                'requests.service-report.start', 'requests.service-report.upload', 'requests.finish',
            ] as $name) {
                $response->assertDontSee('action="'.route($name, $this->propertyRequest).'"', false);
            }
        }
    }

    private function assignInHouse(User $user, string $password): void
    {
        $this->actingAs($user)->patch(route('requests.assignment.update', $this->propertyRequest), [
            'assignment_type' => 'in_house', 'current_password' => $password,
        ])->assertSessionHasNoErrors()->assertRedirect();
        $this->propertyRequest->refresh();
        $this->assertTrue($this->propertyRequest->isInHouse());
    }

    private function saveWorkOrder(User $user, string $password, string $workOrder = 'Replace the damaged lighting fixtures.'): void
    {
        $this->actingAs($user)->patch(route('requests.in-house.work-order', $this->propertyRequest), [
            'in_house_work_order' => $workOrder, 'current_password' => $password,
        ])->assertSessionHasNoErrors()->assertRedirect();
        $this->propertyRequest->refresh();
    }
}
