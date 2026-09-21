<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\RequestAttachment;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DealerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GatewaySystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_matches_the_gateway_entry_point_and_authenticates(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
            'password' => 'Gateway@2026',
        ]);

        $this->get('/login')
            ->assertOk()
            ->assertSee('GATEWAY')
            ->assertSee('Property Management System');

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'Gateway@2026',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_official_workbook_data_seeds_all_seventy_dealers(): void
    {
        $this->seed(DealerSeeder::class);

        $this->assertDatabaseCount('dealers', 70);
        $this->assertDatabaseHas('dealers', [
            'source_no' => 1,
            'name' => 'Mitsubishi Pasig',
            'contact_1' => '0917 874 9226',
        ]);
        $this->assertDatabaseHas('dealers', [
            'source_no' => 70,
            'name' => 'San Fernando Stockyard',
        ]);
    }

    public function test_dealer_can_submit_a_request_for_only_their_branch(): void
    {
        $dealer = $this->dealer();
        $user = User::factory()->create([
            'dealer_id' => $dealer->id,
            'role' => 'dealer',
            'designation' => 'General Manager',
        ]);

        $response = $this->actingAs($user)->post('/requests', [
            'name' => 'Dealer User',
            'designation' => 'General Manager',
            'request_type' => 'Plumbing Works',
            'priority' => 'urgent',
            'description' => 'Repair the leaking line in the customer service area.',
        ]);

        $request = PropertyRequest::firstOrFail();
        $response->assertRedirect(route('requests.show', $request));
        $this->assertSame($dealer->id, $request->dealer_id);
        $this->assertSame('pending', $request->status);
        $this->assertSame('Metro Manila', $request->area);
    }

    public function test_request_form_uses_workfile_presets_and_accepts_no_more_than_five_attachments(): void
    {
        Storage::fake('local');
        $dealer = $this->dealer();
        $user = User::factory()->create([
            'dealer_id' => $dealer->id,
            'role' => 'dealer',
            'designation' => 'General Manager',
        ]);

        $this->actingAs($user)->get(route('requests.create'))
            ->assertOk()
            ->assertSeeInOrder([
                'General Repairs',
                'Electrical Works',
                'Plumbing Works',
                'Carpentry &amp; Woodworks',
                'Painting',
                'Airconditioning',
            ], false)
            ->assertSee('Suggested remarks')
            ->assertDontSee('Use this suggestion')
            ->assertSee('Up to 5 files')
            ->assertDontSee('Safety Inspection')
            ->assertDontSee('Equipment Repair');

        // Unsupported files (e.g. exe) are strictly rejected
        $this->actingAs($user)->from(route('requests.create'))->post(route('requests.store'), [
            'name' => $user->name,
            'designation' => $user->designation,
            'request_type' => 'Painting',
            'priority' => 'regular',
            'description' => 'Repaint the reception wall and match the existing finish.',
            'attachments' => [UploadedFile::fake()->create('contract.exe', 100, 'application/octet-stream')],
        ])->assertRedirect(route('requests.create'))->assertSessionHasErrors('attachments.0');

        $files = collect(range(1, 5))
            ->map(fn (int $number) => $this->fakeImage("request-{$number}.png"))
            ->all();

        $response = $this->actingAs($user)->post(route('requests.store'), [
            'name' => $user->name,
            'designation' => $user->designation,
            'request_type' => 'Painting',
            'priority' => 'regular',
            'description' => 'Repaint the reception wall and match the existing finish.',
            'attachments' => $files,
        ]);

        $propertyRequest = PropertyRequest::firstOrFail();
        $response->assertRedirect(route('requests.show', $propertyRequest));
        $this->assertDatabaseCount('request_attachments', 5);
        $this->assertDatabaseHas('request_attachments', [
            'property_request_id' => $propertyRequest->id,
            'category' => 'request',
            'original_name' => 'request-1.png',
        ]);
        $propertyRequest->attachments->each(fn (RequestAttachment $attachment) => Storage::assertExists($attachment->path));

        $tooManyFiles = collect(range(1, 6))
            ->map(fn (int $number) => $this->fakeImage("extra-{$number}.png"))
            ->all();

        $this->actingAs($user)->from(route('requests.create'))->post(route('requests.store'), [
            'name' => $user->name,
            'designation' => $user->designation,
            'request_type' => 'General Repairs',
            'priority' => 'urgent',
            'description' => 'Repair the damaged service counter before business opens.',
            'attachments' => $tooManyFiles,
        ])->assertRedirect(route('requests.create'))->assertSessionHasErrors('attachments');

        $this->assertDatabaseCount('property_requests', 1);
        $this->assertDatabaseCount('request_attachments', 5);
    }

    public function test_manager_assigns_a_dial_lead_who_must_complete_each_processing_stage_in_order(): void
    {
        Storage::fake('local');
        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer']);
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'pm_manager']);
        $dialA = User::factory()->create(['role' => 'dial_a', 'name' => 'Dial-A']);
        $otherDialA = User::factory()->create(['role' => 'dial_a', 'name' => 'Other Dial-A']);
        $propertyRequest = $this->propertyRequest($dealer, $dealerUser);

        $this->assertSame(
            ['pending', 'pending', 'pending', 'pending'],
            array_column($propertyRequest->activity_progress, 'status')
        );
        $this->assertSame(
            ['Pending', 'Pending', 'Pending', 'Pending'],
            array_column($propertyRequest->activity_progress, 'label')
        );
        $this->actingAs($manager)->get(route('requests.index'))
            ->assertOk()
            ->assertSee('Activity Progress')
            ->assertSee('Inspection');
        $this->actingAs($dialA)->get(route('requests.index'))
            ->assertOk()
            ->assertSee('Activity Progress')
            ->assertSee('Service Report');
        $this->actingAs($dealerUser)->get(route('requests.index'))
            ->assertOk()
            ->assertSee('Activity Progress');
        $this->actingAs($dealerUser)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Activity Progress')
            ->assertSee('Inspection');
        $this->actingAs($dealerUser)->get(route('requests.show', $propertyRequest))
            ->assertOk()
            ->assertSee('Activity Progress')
            ->assertSee('Inspection');

        $propertyRequest->update(['assigned_support_id' => $dialA->id]);

        $assignment = [
            'approved_date' => today()->format('Y-m-d'),
        ];

        $this->actingAs($dealerUser)->patch(route('requests.acknowledge', $propertyRequest), $assignment)->assertForbidden();
        $this->actingAs($admin)->patch(route('requests.acknowledge', $propertyRequest), $assignment)->assertForbidden();
        $this->actingAs($manager)->patch(route('requests.acknowledge', $propertyRequest), $assignment)->assertForbidden();

        $this->actingAs($manager)->get(route('requests.show', $propertyRequest))
            ->assertOk()
            ->assertSee('PM Manager Workflow Controls')
            ->assertSee('Request Assignment')
            ->assertDontSee('Acknowledge &amp; Approve', false)
            ->assertDontSee('Acknowledgement &amp; Approval', false);
        $this->actingAs($dialA)->get(route('requests.show', $propertyRequest))
            ->assertOk()
            ->assertSee('Set Inspection')
            ->assertDontSee('Inspection Completed')
            ->assertSee('Work Order')
            ->assertSee('Service Report');

        $inspectionData = [
            'inspection_date' => today()->format('Y-m-d'),
            // Device-local timestamps may arrive with seconds and are normalized
            // to the minute precision used by the inspection validator.
            'inspection_start_time' => '08:00:17',
            'inspection_end_time' => '09:00:42',
            'inspection_representatives' => ['Gateway Representative', 'Dealer Representative'],
        ];

        $this->actingAs($otherDialA)->patch(route('requests.inspection.complete', $propertyRequest), $inspectionData)->assertForbidden();
        $this->actingAs($dialA)->post(route('requests.work-order.complete', $propertyRequest), [
            'work_order_files' => [$this->fakeImage('work-order.png')],
        ])->assertSessionHasErrors('work_order');

        $inspectionData['inspection_file'] = UploadedFile::fake()->create('completed-inspection.xlsx', 100, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $inspectionData['inspection_template_confirmed'] = '1';
        $this->actingAs($dialA)->patch(route('requests.inspection.complete', $propertyRequest), $inspectionData)->assertRedirect();
        $propertyRequest->refresh();
        $this->assertSame('08:00', substr($propertyRequest->inspection_start_time, 0, 5));
        $this->assertSame('09:00', substr($propertyRequest->inspection_end_time, 0, 5));
        $this->assertSame(
            ['completed', 'on_going', 'pending', 'pending'],
            array_column($propertyRequest->activity_progress, 'status')
        );

        // Non-assigned users cannot complete Work Order
        $this->actingAs($otherDialA)->post(route('requests.work-order.complete', $propertyRequest), [
            'work_order_files' => [$this->fakeImage('work-order.png')],
        ])->assertForbidden();
        $this->actingAs($admin)->post(route('requests.work-order.complete', $propertyRequest), [
            'work_order_files' => [$this->fakeImage('work-order.png')],
        ])->assertForbidden();
        $this->actingAs($dealerUser)->post(route('requests.work-order.complete', $propertyRequest), [
            'work_order_files' => [$this->fakeImage('work-order.png')],
        ])->assertForbidden();

        // Assigned Dial-A can complete Work Order (with workbook or image)
        $this->actingAs($dialA)->post(route('requests.work-order.complete', $propertyRequest), [
            'work_order_start_date' => today()->format('Y-m-d'),
            'work_order_end_date' => today()->format('Y-m-d'),
            'work_order_representatives' => ['Dial-A', 'Gateway Representative'],
            'work_order_files' => [UploadedFile::fake()->create('work-order.pdf', 100, 'application/pdf')],
        ])->assertRedirect();
        $propertyRequest->refresh();
        $this->assertSame(
            ['completed', 'completed', 'on_going', 'pending'],
            array_column($propertyRequest->activity_progress, 'status')
        );
        $this->assertSame(
            ['Completed', 'Completed', 'On-going', 'Pending'],
            array_column($propertyRequest->activity_progress, 'label')
        );
        $this->actingAs($dialA)->get(route('requests.show', $propertyRequest))->assertOk()->assertSee('Done Service Report');

        // Other users cannot upload Service Reports
        $this->actingAs($admin)->post(route('requests.service-report.complete', $propertyRequest), [
            'service_report_files' => [$this->fakeImage('service-report.png')],
        ])->assertForbidden();
        $this->actingAs($dealerUser)->post(route('requests.service-report.complete', $propertyRequest), [
            'service_report_files' => [$this->fakeImage('service-report.png')],
        ])->assertForbidden();

        $this->actingAs($dialA)->post(route('requests.service-report.complete', $propertyRequest), [
            'service_report_files' => [UploadedFile::fake()->create('service-report.docx', 100, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')],
        ])->assertRedirect();

        $propertyRequest->refresh();
        $this->assertSame('completed', $propertyRequest->status);
        $this->assertNotNull($propertyRequest->service_report_completed_at);
        $this->assertNotNull($propertyRequest->completed_at);

        // Dealer/Admin/Other cannot finish the request
        $this->actingAs($dealerUser)->post(route('requests.finish', $propertyRequest))->assertForbidden();
        $this->actingAs($admin)->post(route('requests.finish', $propertyRequest))->assertForbidden();
        $this->actingAs($otherDialA)->post(route('requests.finish', $propertyRequest))->assertForbidden();

        // Only assigned Dial-A confirms and finishes the request
        $this->actingAs($dialA)->post(route('requests.finish', $propertyRequest))->assertRedirect();

        $propertyRequest->refresh();
        $this->assertSame('completed', $propertyRequest->status);
        $this->assertSame($dialA->id, $propertyRequest->assigned_support_id);
        $this->assertNotNull($propertyRequest->inspection_completed_at);
        $this->assertNotNull($propertyRequest->work_order_completed_at);
        $this->assertNotNull($propertyRequest->service_report_completed_at);
        $this->assertNotNull($propertyRequest->completed_at);
        $this->assertSame(
            ['completed', 'completed', 'completed', 'completed'],
            array_column($propertyRequest->activity_progress, 'status')
        );
        $this->assertSame(
            ['Completed', 'Completed', 'Completed', 'Completed'],
            array_column($propertyRequest->activity_progress, 'label')
        );
        $this->assertDatabaseHas('request_attachments', ['property_request_id' => $propertyRequest->id, 'category' => 'inspection', 'original_name' => 'completed-inspection.xlsx']);
        $this->assertDatabaseHas('request_attachments', ['property_request_id' => $propertyRequest->id, 'category' => 'work_order', 'original_name' => 'work-order.pdf']);
        $this->assertDatabaseHas('request_attachments', ['property_request_id' => $propertyRequest->id, 'category' => 'service_report', 'original_name' => 'service-report.docx']);
    }

    public function test_pm_manager_gets_the_assignment_view_but_not_pm_support_actions(): void
    {
        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer']);
        $manager = User::factory()->create(['role' => 'pm_manager']);
        $propertyRequest = $this->propertyRequest($dealer, $dealerUser);

        $this->actingAs($manager)->get(route('dashboard'))->assertOk()->assertSee('Recent Request Activity');
        $this->actingAs($manager)->get(route('requests.index'))->assertOk()->assertSee($propertyRequest->reference_no);
        $this->actingAs($manager)->get(route('requests.show', $propertyRequest))
            ->assertOk()
            ->assertSee('PM Manager Workflow Controls')
            ->assertSee('Request Assignment')
            ->assertDontSee('Acknowledge &amp; Approve', false)
            ->assertDontSee('Acknowledgement &amp; Approval', false);
        $this->actingAs($manager)->get(route('dealers.index'))->assertOk();

        $this->actingAs($manager)->patch(route('requests.acknowledge', $propertyRequest), ['approved_date' => today()])->assertForbidden();
        $this->actingAs($manager)->patch(route('requests.assign', $propertyRequest))->assertForbidden();
        $this->actingAs($manager)->patch(route('requests.status', $propertyRequest), ['status' => 'completed'])->assertForbidden();
        $this->actingAs($manager)->post(route('requests.work-order.complete', $propertyRequest), [
            'work_order_files' => [$this->fakeImage('work-order.png')],
        ])->assertForbidden();
        $this->actingAs($manager)->post(route('requests.service-report.complete', $propertyRequest), [
            'service_report_files' => [$this->fakeImage('service-report.png')],
        ])->assertForbidden();
    }

    public function test_pm_manager_can_only_view_and_publish_print_reports_for_done_requests_with_dial_a_attachments(): void
    {
        Storage::fake('local');

        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer']);
        $manager = User::factory()->create(['role' => 'pm_manager', 'name' => 'PM Manager']);
        $support = User::factory()->create(['role' => 'pm_support', 'name' => 'Dial Lead Alpha']);
        $admin = User::factory()->create(['role' => 'admin']);

        // Pending request (not done)
        $pendingRequest = $this->propertyRequest($dealer, $dealerUser);

        // Completed request (done) with Dial-A attachments
        $completedRequest = PropertyRequest::create([
            'reference_no' => 'GPM-20260915-0099',
            'dealer_id' => $dealer->id,
            'submitted_by' => $dealerUser->id,
            'assigned_support_id' => $support->id,
            'assigned_manager_id' => $manager->id,
            'submitter_name' => $dealerUser->name,
            'designation' => 'Dealer Representative',
            'branch' => $dealer->name,
            'area' => $dealer->area,
            'request_type' => 'Airconditioning',
            'priority' => 'urgent',
            'description' => 'Aircon compressor unit replacement completed.',
            'request_date' => today()->subDays(5),
            'due_date' => today()->subDays(1),
            'approved_date' => today()->subDays(4),
            'inspection_date' => today()->subDays(3),
            'representative_1' => 'Rep One',
            'inspection_completed_at' => now()->subDays(3),
            'work_order_completed_at' => now()->subDays(2),
            'service_report_completed_at' => now()->subDay(),
            'status' => 'completed',
            'completed_at' => now()->subDay(),
        ]);

        Storage::put('request-attachments/work_order/wo.pdf', 'work order content');
        $completedRequest->attachments()->create([
            'category' => 'work_order',
            'path' => 'request-attachments/work_order/wo.pdf',
            'original_name' => 'work-order-sample.pdf',
            'mime_type' => 'application/pdf',
            'size' => 102400,
        ]);

        Storage::put('request-attachments/service_report/sr.png', 'service report content');
        $completedRequest->attachments()->create([
            'category' => 'service_report',
            'path' => 'request-attachments/service_report/sr.png',
            'original_name' => 'service-report-photo.png',
            'mime_type' => 'image/png',
            'size' => 204800,
        ]);

        Storage::put('request-attachments/request/dealer-issue.png', 'dealer issue photo');
        $completedRequest->attachments()->create([
            'category' => 'request',
            'path' => 'request-attachments/request/dealer-issue.png',
            'original_name' => 'dealer-issue.png',
            'mime_type' => 'image/png',
            'size' => 153600,
        ]);

        // PM Manager visits /reports: should only see done requests, with attachments, and print-only action
        $this->actingAs($manager)->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Request Operations Report')
            ->assertSee('Print / Publish Report')
            ->assertDontSee('Download .xlsx')
            ->assertSee($completedRequest->reference_no)
            ->assertDontSee($pendingRequest->reference_no)
            ->assertSee('dealer-issue.png')
            ->assertSee('work-order-sample.pdf')
            ->assertSee('service-report-photo.png');

        // PM Manager prints / publishes report: includes actual picture of request attachment
        $this->actingAs($manager)->get(route('reports.print'))
            ->assertOk()
            ->assertSee('Completed Requests Operations Report')
            ->assertSee('Print / Publish Report (PDF)')
            ->assertSee('Request Photos / Attachments')
            ->assertSee('dealer-issue.png')
            ->assertSee('print-actual-picture')
            ->assertSee(route('attachments.show', $completedRequest->requestFiles->first()))
            ->assertSee($completedRequest->reference_no)
            ->assertDontSee($pendingRequest->reference_no)
            ->assertSee('work-order-sample.pdf')
            ->assertSee('service-report-photo.png');

        // Verify audit log recorded for report publishing
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $manager->id,
            'action' => 'report_published',
        ]);

        // Verify /reports/download does not exist (not with the xlsx file)
        $this->actingAs($manager)->get('/reports/download')->assertNotFound();

        // Other roles cannot view or publish reports
        foreach ([$support, $admin, $dealerUser] as $unauthorizedUser) {
            $this->actingAs($unauthorizedUser)->get(route('reports.index'))->assertForbidden();
            $this->actingAs($unauthorizedUser)->get(route('reports.print'))->assertForbidden();
        }
    }

    public function test_dealer_cannot_view_another_dealers_request_or_directory(): void
    {
        $dealerA = $this->dealer();
        $dealerB = Dealer::create([
            'source_no' => 2,
            'name' => 'Dealer B',
            'area' => 'South Luzon',
            'brand' => 'Gateway Operations',
        ]);
        $userA = User::factory()->create(['dealer_id' => $dealerA->id, 'role' => 'dealer']);
        $userB = User::factory()->create(['dealer_id' => $dealerB->id, 'role' => 'dealer']);
        $propertyRequest = $this->propertyRequest($dealerB, $userB);

        $this->actingAs($userA)->get(route('requests.show', $propertyRequest))->assertForbidden();
        $this->actingAs($userA)->get(route('dealers.index'))->assertForbidden();
    }

    public function test_only_admin_can_reset_request_data_without_deleting_dealers_or_users(): void
    {
        Storage::fake('local');

        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer']);
        $support = User::factory()->create(['role' => 'pm_support']);
        $manager = User::factory()->create(['role' => 'pm_manager']);
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => 'AdminReset@2026',
        ]);
        $propertyRequest = $this->propertyRequest($dealer, $dealerUser);
        Storage::put('request-attachments/test-photo.png', 'request image');
        RequestAttachment::create([
            'property_request_id' => $propertyRequest->id,
            'path' => 'request-attachments/test-photo.png',
            'original_name' => 'test-photo.png',
            'mime_type' => 'image/png',
            'size' => 13,
        ]);
        AuditLog::create([
            'user_id' => $dealerUser->id,
            'action' => 'request_submitted',
            'subject_type' => 'PropertyRequest',
            'subject_id' => $propertyRequest->id,
            'description' => 'A request was submitted.',
        ]);
        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'login',
            'description' => 'Administrator signed in.',
        ]);

        foreach ([$dealerUser, $support, $manager] as $unauthorizedUser) {
            $this->actingAs($unauthorizedUser)->get(route('admin.reset-requests.show'))->assertForbidden();
            $this->actingAs($unauthorizedUser)->delete(route('admin.reset-requests.destroy'), [
                'confirmation' => 'RESET REQUESTS',
                'current_password' => 'AdminReset@2026',
            ])->assertForbidden();
        }

        $this->actingAs($admin)->get(route('admin.reset-requests.show'))
            ->assertOk()
            ->assertSee('Reset Requests')
            ->assertSee('Will be preserved');

        $this->actingAs($admin)->from(route('admin.reset-requests.show'))->delete(route('admin.reset-requests.destroy'), [
            'confirmation' => 'RESET',
            'current_password' => 'AdminReset@2026',
        ])->assertRedirect(route('admin.reset-requests.show'));
        $this->assertDatabaseCount('property_requests', 1);

        $dealerCount = Dealer::count();
        $userCount = User::count();
        $this->actingAs($admin)->delete(route('admin.reset-requests.destroy'), [
            'confirmation' => 'RESET REQUESTS',
            'current_password' => 'AdminReset@2026',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseCount('property_requests', 0);
        $this->assertDatabaseCount('request_attachments', 0);
        $this->assertSame($dealerCount, Dealer::count());
        $this->assertSame($userCount, User::count());
        $this->assertDatabaseHas('dealers', ['name' => 'Mitsubishi Pasig']);
        $this->assertDatabaseHas('audit_logs', ['action' => 'login']);
        $this->assertDatabaseHas('audit_logs', ['action' => 'requests_reset']);
        $this->assertDatabaseMissing('audit_logs', ['action' => 'request_submitted']);
        Storage::assertMissing('request-attachments/test-photo.png');
    }

    public function test_every_tab_renders_for_the_correct_role(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(1, User::where('role', 'dial_a')->where('name', 'Dial-A')->count());
        $this->assertSame(0, User::where('role', 'representative')->count());
        $this->assertSame(0, User::where('role', 'handyman')->count());

        $admin = User::where('role', 'admin')->firstOrFail();
        foreach ([
            route('dashboard'), route('requests.index'), route('dealers.index'),
            route('admin.areas'), route('admin.users'), route('admin.roles'),
            route('admin.audit'), route('admin.settings'), route('admin.reset-requests.show'),
            route('profile.edit'),
        ] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }

        $support = User::where('role', 'dial_a')->firstOrFail();
        foreach ([route('dashboard'), route('requests.index'), route('dealers.index'), route('profile.edit')] as $url) {
            $this->actingAs($support)->get($url)->assertOk();
        }

        $manager = User::where('role', 'pm_manager')->firstOrFail();
        foreach ([route('dashboard'), route('requests.index'), route('dealers.index'), route('reports.index'), route('reports.print'), route('profile.edit')] as $url) {
            $this->actingAs($manager)->get($url)->assertOk();
        }

        $dealer = User::where('email', 'dealer.mitsubishi-pasig@gateway.ph')->firstOrFail();
        foreach ([route('dashboard'), route('requests.index'), route('requests.create'), route('profile.edit')] as $url) {
            $this->actingAs($dealer)->get($url)->assertOk();
        }

        $ownRequest = $this->propertyRequest($dealer->dealer, $dealer);
        $this->actingAs($dealer)->get(route('requests.show', $ownRequest))->assertOk();
    }

    public function test_activity_progress_is_displayed_for_dealers_and_uses_updated_labels(): void
    {
        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer']);
        $support = User::factory()->create(['role' => 'pm_support']);
        $propertyRequest = $this->propertyRequest($dealer, $dealerUser);

        // A newly submitted request has not been interacted with yet.
        $this->assertSame(['Pending', 'Pending', 'Pending', 'Pending'], array_column($propertyRequest->activity_progress, 'label'));

        // Dealer Dashboard displays activity progress
        $this->actingAs($dealerUser)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Activity Progress')
            ->assertSee('Inspection')
            ->assertSee('Work Order')
            ->assertSee('Service Report');

        // Dealer Requests list (My Requests) displays activity progress column
        $this->actingAs($dealerUser)->get(route('requests.index'))
            ->assertOk()
            ->assertSee('Activity Progress')
            ->assertSee('Inspection')
            ->assertSee('Pending');

        // Dealer Request Show view displays activity progress section
        $this->actingAs($dealerUser)->get(route('requests.show', $propertyRequest))
            ->assertOk()
            ->assertSee('Activity Progress')
            ->assertSee('Workbook workflow')
            ->assertSee('Inspection')
            ->assertSee('Service Report');

        // Once the request is interacted with, Inspection becomes On-going.
        $propertyRequest->update(['status' => 'in_progress']);
        $propertyRequest->refresh();
        $this->assertSame(['On-going', 'Pending', 'Pending', 'Pending'], array_column($propertyRequest->activity_progress, 'label'));

        // Progress step: Inspection completed, Work Order in progress.
        $propertyRequest->update([
            'assigned_support_id' => $support->id,
            'inspection_date' => today(),
            'inspection_completed_at' => now(),
        ]);
        $propertyRequest->refresh();
        $this->assertSame(['Completed', 'On-going', 'Pending', 'Pending'], array_column($propertyRequest->activity_progress, 'label'));

        $this->actingAs($dealerUser)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Completed')
            ->assertSee('On-going');

        $this->actingAs($dealerUser)->get(route('requests.index'))
            ->assertOk()
            ->assertSee('Completed')
            ->assertSee('On-going');

        $this->actingAs($dealerUser)->get(route('requests.show', $propertyRequest))
            ->assertOk()
            ->assertSee('Completed')
            ->assertSee('On-going');
    }

    public function test_overall_completion_date_and_time_are_displayed_on_request_list_and_details(): void
    {
        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer']);
        $propertyRequest = $this->propertyRequest($dealer, $dealerUser);
        $propertyRequest->update([
            'status' => 'completed',
            'completed_at' => '2026-09-16 14:35:00',
        ]);

        $this->actingAs($dealerUser)->get(route('requests.index'))
            ->assertOk()
            ->assertSee('Activity Completion Date &amp; Time', false)
            ->assertSee('Sep 16, 2026')
            ->assertSee('02:35 PM');

        $this->actingAs($dealerUser)->get(route('requests.show', $propertyRequest))
            ->assertOk()
            ->assertSee('Completion Date &amp; Time', false)
            ->assertSee('Sep 16, 2026 02:35 PM');

        $propertyRequest->update([
            'status' => 'in_progress',
            'completed_at' => null,
        ]);

        $this->actingAs($dealerUser)->get(route('requests.show', $propertyRequest))
            ->assertOk()
            ->assertSee('Not completed');
    }

    public function test_dealer_can_review_completed_workflow_details_representatives_and_attachments(): void
    {
        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer']);
        $dialA = User::factory()->create(['role' => 'dial_a', 'name' => 'Assigned Dial-A']);
        $propertyRequest = $this->propertyRequest($dealer, $dealerUser);

        $propertyRequest->update([
            'assigned_support_id' => $dialA->id,
            'inspection_date' => '2026-09-10',
            'inspection_start_time' => '08:15:00',
            'representative_1' => 'Inspection Representative',
            'inspection_completed_at' => '2026-09-10 10:30:00',
            'work_order_start_date' => '2026-09-11',
            'work_order_start_time' => '09:00:00',
            'work_order_representatives' => ['Work Representative'],
            'work_order_completed_at' => '2026-09-11 16:45:00',
            'service_report_completed_at' => '2026-09-12 11:20:00',
            'completed_at' => '2026-09-12 11:25:00',
            'status' => 'completed',
        ]);

        foreach (['inspection', 'work_order', 'service_report'] as $category) {
            $propertyRequest->attachments()->create([
                'category' => $category,
                'path' => "request-attachments/{$category}/{$category}.pdf",
                'original_name' => "{$category}.pdf",
                'mime_type' => 'application/pdf',
                'size' => 100,
            ]);
        }

        $this->actingAs($dealerUser)->get(route('requests.show', $propertyRequest))
            ->assertOk()
            ->assertSee('Completed Request Record')
            ->assertSee('Assigned Dial-A')
            ->assertSee('Inspection Representative')
            ->assertSee('Work Representative')
            ->assertSee('Sep 10, 2026 10:30 AM')
            ->assertSee('Sep 11, 2026 04:45 PM')
            ->assertSee('Sep 12, 2026 11:20 AM')
            ->assertSee('Sep 12, 2026 11:25 AM')
            ->assertSee('inspection.pdf')
            ->assertSee('work_order.pdf')
            ->assertSee('service_report.pdf');
    }

    public function test_pm_review_assigns_dealer_request_to_dial_a_before_inspection(): void
    {
        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer']);
        $designatedLead = User::factory()->create(['role' => 'dial_a', 'name' => 'Dial-A']);
        $manager = User::factory()->create(['role' => 'pm_manager', 'name' => 'PM Manager']);

        // 1. Dealer submits request -> automatically assigned to designated Dial-A
        $this->actingAs($dealerUser)->post(route('requests.store'), [
            'name' => $dealerUser->name,
            'designation' => 'Dealer General Manager',
            'request_type' => 'Electrical Works',
            'priority' => 'urgent',
            'description' => 'Replace circuit breakers in main panel.',
        ])->assertRedirect();

        $request = PropertyRequest::latest()->firstOrFail();
        $this->assertNull($request->assigned_support_id);
        $this->assertTrue($request->isAwaitingPmReview());
        $this->assertSame('pending', $request->status);

        $this->actingAs($designatedLead)->get(route('requests.show', $request))->assertForbidden();
        $this->actingAs($manager)->patch(route('requests.assignment.update', $request), [
            'assignment_type' => 'dial_a', 'priority' => 'urgent', 'remarks' => 'Inspection is needed for an electrical safety risk.',
            'current_password' => 'password',
        ])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame($designatedLead->id, $request->fresh()->assigned_support_id);
        $this->post(route('requests.assignment.proceed', $request), ['current_password' => 'password'])->assertSessionHasNoErrors()->assertRedirect();

        // 2. PM Manager views request: strictly view-only, no acknowledgement approval form
        $this->actingAs($manager)->get(route('requests.show', $request))
            ->assertOk()
            ->assertSee('PM Manager Workflow Controls')
            ->assertSee('Request Assignment')
            ->assertDontSee('Acknowledge &amp; Approve', false)
            ->assertDontSee('Acknowledgement &amp; Approval', false);

        // PM Manager cannot acknowledge or approve request
        $this->actingAs($manager)->patch(route('requests.acknowledge', $request), [
            'approved_date' => today()->format('Y-m-d'),
        ])->assertForbidden();

        // 3. Dial-A conducts inspection directly with free-text representatives (no PM Manager approval needed)
        $this->actingAs($designatedLead)->get(route('requests.show', $request))
            ->assertOk()
            ->assertDontSee('Awaiting PM Manager Acknowledgement')
            ->assertSee('Set Inspection')
            ->assertDontSee('Inspection Completed');

        $this->actingAs($designatedLead)->patch(route('requests.inspection.complete', $request), [
            'inspection_date' => today()->format('Y-m-d'),
            'inspection_start_time' => '08:00',
            'inspection_end_time' => '09:00',
            'inspection_representatives' => ['Field Representative Alpha', 'Assistant Rep Beta'],
            'inspection_file' => UploadedFile::fake()->create('completed-inspection.xlsx', 100, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
            'inspection_template_confirmed' => '1',
        ])->assertRedirect();

        $request->refresh();
        $this->assertNotNull($request->inspection_completed_at);
        $this->assertSame('Field Representative Alpha', $request->representative_1);
        $this->assertSame('Assistant Rep Beta', $request->representative_2);

        // 4. Dial-A sees completed inspection summary with free-text representatives
        $this->actingAs($designatedLead)->get(route('requests.show', $request))
            ->assertOk()
            ->assertSee('Field Representative Alpha')
            ->assertSee('Assistant Rep Beta');
    }

    public function test_dial_a_is_exclusively_responsible_for_work_order_and_service_report_with_document_attachments(): void
    {
        Storage::fake('local');
        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer']);
        $manager = User::factory()->create(['role' => 'pm_manager']);
        $dialA = User::factory()->create(['role' => 'dial_a', 'name' => 'Dial-A User']);
        $admin = User::factory()->create(['role' => 'admin']);

        $request = PropertyRequest::create([
            'reference_no' => 'GPM-20260915-0888',
            'dealer_id' => $dealer->id,
            'submitted_by' => $dealerUser->id,
            'assigned_support_id' => $dialA->id,
            'assigned_manager_id' => $manager->id,
            'submitter_name' => $dealerUser->name,
            'designation' => 'Dealer Representative',
            'branch' => $dealer->name,
            'area' => $dealer->area,
            'request_type' => 'Electrical Works',
            'priority' => 'regular',
            'description' => 'Test document attachments and role responsibility.',
            'request_date' => today(),
            'due_date' => today()->addDays(7),
            'approved_date' => today(),
            'inspection_date' => today(),
            'representative_1' => 'Field Rep Alpha',
            'inspection_completed_at' => now(),
            'status' => 'in_progress',
        ]);

        // 1. Only Dial-A can do Work Order attachments (Manager, Admin, Dealer are forbidden)
        $unauthorizedWorkOrderUsers = [$manager, $admin, $dealerUser];
        foreach ($unauthorizedWorkOrderUsers as $unauthorized) {
            $this->actingAs($unauthorized)->post(route('requests.work-order.complete', $request), [
                'work_order_files' => [$this->fakeImage('order.png')],
            ])->assertForbidden();
        }

        // 2. Dial-A can submit non-image documents for Work Order
        $this->actingAs($dialA)->post(route('requests.work-order.complete', $request), [
            'work_order_start_date' => today()->format('Y-m-d'),
            'work_order_end_date' => today()->format('Y-m-d'),
            'work_order_representatives' => ['Dial-A User'],
            'work_order_files' => [
                UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf'),
                UploadedFile::fake()->create('spreadsheet.xlsx', 100, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
            ],
        ])->assertRedirect();

        $request->refresh();
        $this->assertNotNull($request->work_order_completed_at);
        $this->assertSame(2, $request->workOrderFiles()->count());

        // 4. Service Report should be strictly only to Dial-A (Manager, Admin, Dealer are forbidden)
        $unauthorizedServiceUsers = [$manager, $admin, $dealerUser];
        foreach ($unauthorizedServiceUsers as $unauthorized) {
            $this->actingAs($unauthorized)->post(route('requests.service-report.complete', $request), [
                'service_report_files' => [$this->fakeImage('service.png')],
            ])->assertForbidden();
        }

        // 5. Dial-A can submit a non-image document for Service Report
        $this->actingAs($dialA)->post(route('requests.service-report.complete', $request), [
            'service_report_files' => [UploadedFile::fake()->create('report.pdf', 100, 'application/pdf')],
        ])->assertRedirect();

        $request->refresh();
        $this->assertNotNull($request->service_report_completed_at);
        $this->assertNotNull($request->completed_at);
        $this->assertSame('completed', $request->status);
        $this->assertSame(1, $request->serviceReportFiles()->count());

        // 7. Non-Dial-A users CANNOT finish the request
        foreach ([$manager, $admin, $dealerUser] as $unauthorized) {
            $this->actingAs($unauthorized)->post(route('requests.finish', $request))->assertForbidden();
        }

        // 8. Dial-A confirms and finishes the request
        $this->actingAs($dialA)->post(route('requests.finish', $request))->assertRedirect();

        $request->refresh();
        $this->assertSame('completed', $request->status);
        $this->assertNotNull($request->completed_at);
    }

    public function test_pm_manager_monitors_dial_a_and_report_printing_includes_request_photos(): void
    {
        Storage::fake('local');

        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer']);
        $manager = User::factory()->create(['role' => 'pm_manager', 'name' => 'PM Manager']);
        $support = User::factory()->create(['role' => 'pm_support', 'name' => 'Dial Lead Alpha']);

        // 1. Create a completed request with dealer request photo attachment
        $request = PropertyRequest::create([
            'reference_no' => 'GPM-20260915-0555',
            'dealer_id' => $dealer->id,
            'submitted_by' => $dealerUser->id,
            'assigned_support_id' => $support->id,
            'submitter_name' => $dealerUser->name,
            'designation' => 'Dealer General Manager',
            'branch' => $dealer->name,
            'area' => $dealer->area,
            'request_type' => 'Airconditioning',
            'priority' => 'urgent',
            'description' => 'Server room AC condenser unit failure.',
            'request_date' => today()->subDays(3),
            'due_date' => today(),
            'inspection_date' => today()->subDays(2),
            'representative_1' => 'Tech Rep',
            'inspection_completed_at' => now()->subDays(2),
            'work_order_completed_at' => now()->subDay(),
            'service_report_completed_at' => now(),
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        Storage::put('request-attachments/request/ac-damage.jpg', 'fake image bytes');
        $reqPhoto = $request->attachments()->create([
            'category' => 'request',
            'path' => 'request-attachments/request/ac-damage.jpg',
            'original_name' => 'ac-damage.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 307200,
        ]);

        Storage::put('request-attachments/work_order/wo-proof.png', 'fake wo bytes');
        $request->attachments()->create([
            'category' => 'work_order',
            'path' => 'request-attachments/work_order/wo-proof.png',
            'original_name' => 'wo-proof.png',
            'mime_type' => 'image/png',
            'size' => 102400,
        ]);

        Storage::put('request-attachments/service_report/sr-proof.png', 'fake sr bytes');
        $request->attachments()->create([
            'category' => 'service_report',
            'path' => 'request-attachments/service_report/sr-proof.png',
            'original_name' => 'sr-proof.png',
            'mime_type' => 'image/png',
            'size' => 204800,
        ]);

        // 2. Verify PM Manager cannot acknowledge or edit request
        $this->actingAs($manager)->patch(route('requests.acknowledge', $request), [
            'approved_date' => today()->format('Y-m-d'),
        ])->assertForbidden();

        // 3. PM Manager can assign requests, while Dial-A retains its own workflow actions.
        $this->actingAs($manager)->get(route('requests.show', $request))
            ->assertOk()
            ->assertSee('PM Manager Workflow Controls')
            ->assertSee('Request Assignment')
            ->assertDontSee('Acknowledge &amp; Approve', false)
            ->assertDontSee('Pending Acknowledgement')
            ->assertSee('Inspection Details')
            ->assertSee('wo-proof.png')
            ->assertSee('sr-proof.png');

        // 4. Verify report printing includes actual picture of the request attachment on dedicated pages
        $printResponse = $this->actingAs($manager)->get(route('reports.print'));
        $printResponse->assertOk()
            ->assertSee('Completed Requests Operations Report')
            ->assertSee('Request Photos / Attachments')
            ->assertSee('ac-damage.jpg')
            ->assertSee('print-actual-picture')
            ->assertSee(route('attachments.show', $reqPhoto))
            ->assertSee('wo-proof.png')
            ->assertSee('sr-proof.png')
            ->assertSee('no-bg-gateway-logo.png')
            ->assertSee('brand-wordmark')
            ->assertSee('attachment-single-page')
            ->assertSee('attachment-main-title')
            ->assertSee('Request Attachment: ac-damage.jpg')
            ->assertSee('Dial-A Work Order Attachment: wo-proof.png')
            ->assertSee('Dial-A Service Report Attachment: sr-proof.png');

        // Verify images are not inside the table
        $html = $printResponse->getContent();
        preg_match('/<tbody>(.*?)<\/tbody>/s', $html, $matches);
        $this->assertNotEmpty($matches);
        $this->assertStringNotContainsString('<img', $matches[1]);

        // 5. Verify reports preview page shows request attachments
        $this->actingAs($manager)->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Request Attachments')
            ->assertSee('ac-damage.jpg');
    }

    public function test_all_user_tables_render_correct_column_sequence(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('role', 'admin')->firstOrFail();
        $support = User::whereIn('role', ['dial_a', 'pm_support'])->firstOrFail();
        $manager = User::where('role', 'pm_manager')->firstOrFail();
        $dealerUser = User::where('email', 'dealer.mitsubishi-pasig@gateway.ph')->firstOrFail();

        $propertyRequest = $this->propertyRequest($dealerUser->dealer, $dealerUser);
        $propertyRequest->update([
            'assigned_support_id' => $support->id,
            'request_type' => 'Plumbing Works',
            'priority' => 'urgent',
        ]);

        $roles = [$dealerUser, $admin, $manager, $support];

        foreach ($roles as $user) {
            $response = $this->actingAs($user)->get(route('requests.index'));
            $response->assertOk();

            // Verify column headers in exact sequence
            $content = $response->getContent();
            $expectedHeaders = [
                'REQUEST NO.',
                'BRANCH / AREA',
                'REPAIR CATEGORY',
                'PRIORITY',
                'Request Submission Date &amp; Time',
                'Activity Completion Date &amp; Time',
                'Activity Progress',
            ];

            $lastPos = 0;
            foreach ($expectedHeaders as $header) {
                $pos = strpos($content, $header, $lastPos);
                $this->assertNotFalse($pos, "Header '{$header}' was not found in correct sequence for user role {$user->role}");
                $lastPos = $pos + strlen($header);
            }

            // Verify row contents
            $response->assertSee($propertyRequest->reference_no)
                ->assertSee($propertyRequest->dealer->name)
                ->assertSee($propertyRequest->dealer->area)
                ->assertSee('Plumbing Works')
                ->assertSee('Urgent')
                ->assertSee($propertyRequest->created_at->format('M d, Y'))
                ->assertSee($propertyRequest->created_at->format('h:i A'));
        }

        // Test filter search by ticket number, subject, and branch
        $this->actingAs($dealerUser)->get(route('requests.index', ['search' => 'Plumbing Works']))
            ->assertOk()
            ->assertSee($propertyRequest->reference_no);

        $this->actingAs($dealerUser)->get(route('requests.index', ['search' => $propertyRequest->reference_no]))
            ->assertOk()
            ->assertSee('Plumbing Works');

        $this->actingAs($dealerUser)->get(route('requests.index', ['search' => $propertyRequest->dealer->name]))
            ->assertOk()
            ->assertSee($propertyRequest->reference_no);
    }

    public function test_dial_lead_only_work_order_and_dial_a_strictly_service_report_and_dial_a_notify_button(): void
    {
        Storage::fake('local');
        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer']);
        $dialA = User::factory()->create(['role' => 'dial_a', 'name' => 'Dial-A User']);
        $manager = User::factory()->create(['role' => 'pm_manager', 'name' => 'PM Manager']);
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'System Admin']);

        $request = PropertyRequest::create([
            'reference_no' => 'GPM-20260915-7777',
            'dealer_id' => $dealer->id,
            'submitted_by' => $dealerUser->id,
            'assigned_support_id' => $dialA->id,
            'submitter_name' => $dealerUser->name,
            'designation' => 'Dealer General Manager',
            'branch' => $dealer->name,
            'area' => $dealer->area,
            'request_type' => 'Airconditioning',
            'priority' => 'urgent',
            'description' => 'Clean filters and overhaul blower unit.',
            'request_date' => today(),
            'due_date' => today()->addDays(2),
            'inspection_date' => today(),
            'representative_1' => 'Gateway Tech 1',
            'inspection_completed_at' => now(),
            'status' => 'in_progress',
        ]);

        // --- STAGE 2: WORK ORDER (DIAL-A) ---
        // Non-Dial-A users cannot upload Work Orders
        foreach ([$manager, $admin, $dealerUser] as $unauthorized) {
            $this->actingAs($unauthorized)->post(route('requests.work-order.complete', $request), [
                'work_order_files' => [$this->fakeImage('wo.png')],
            ])->assertForbidden();
        }

        // Dial-A uploads Work Order images
        $this->actingAs($dialA)->post(route('requests.work-order.complete', $request), [
            'work_order_start_date' => today()->format('Y-m-d'),
            'work_order_end_date' => today()->format('Y-m-d'),
            'work_order_representatives' => ['Dial-A User'],
            'work_order_files' => [$this->fakeImage('work-order-signed.png')],
        ])->assertRedirect();

        $request->refresh();
        $this->assertNotNull($request->work_order_completed_at);
        $this->assertSame(1, $request->workOrderFiles()->count());

        // --- STAGE 3: SERVICE REPORT (DIAL-A) ---
        // Non-Dial-A users cannot upload Service Reports
        foreach ([$manager, $admin, $dealerUser] as $unauthorized) {
            $this->actingAs($unauthorized)->post(route('requests.service-report.complete', $request), [
                'service_report_files' => [$this->fakeImage('sr.png')],
            ])->assertForbidden();
        }

        // Dial-A uploads Service Report images
        $this->actingAs($dialA)->post(route('requests.service-report.complete', $request), [
            'service_report_files' => [$this->fakeImage('service-report-signed.png')],
        ])->assertRedirect();

        $request->refresh();
        $this->assertNotNull($request->service_report_completed_at);
        $this->assertSame(1, $request->serviceReportFiles()->count());
        $this->assertSame('completed', $request->status);
        $this->assertNotNull($request->completed_at);

        // --- STAGE 4: DIAL-A CONFIRMATION & COMPLETION ---
        // Dial-A sees the completed stage and the finish button is removed
        $this->actingAs($dialA)->get(route('requests.show', $request))
            ->assertOk()
            ->assertSee('Request Completed')
            ->assertDontSee('Confirm &amp; Finish Request', false);

        // Non-Dial-A users cannot finish the request
        foreach ([$manager, $admin, $dealerUser] as $unauthorized) {
            $this->actingAs($unauthorized)->post(route('requests.finish', $request))->assertForbidden();
        }

        // Dial-A confirms and finishes the request
        $this->actingAs($dialA)->post(route('requests.finish', $request))->assertRedirect();

        $request->refresh();
        $this->assertSame('completed', $request->status);
        $this->assertNotNull($request->completed_at);

        // Verify audit log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'request_completed',
            'user_id' => $dialA->id,
        ]);
    }

    public function test_inspection_puts_request_in_progress_and_requires_attachments_before_confirmation(): void
    {
        Storage::fake('local');
        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer']);
        $dialA = User::factory()->create(['role' => 'dial_a', 'name' => 'Dial-A Lead']);

        $request = PropertyRequest::create([
            'reference_no' => 'GPM-20260915-7777',
            'dealer_id' => $dealer->id,
            'submitted_by' => $dealerUser->id,
            'assigned_support_id' => $dialA->id,
            'submitter_name' => $dealerUser->name,
            'designation' => 'Dealer Staff',
            'branch' => $dealer->name,
            'area' => $dealer->area,
            'request_type' => 'Electrical Works',
            'priority' => 'regular',
            'description' => 'Fix power circuit breaker.',
            'request_date' => today(),
            'due_date' => today()->addDays(3),
            'status' => 'pending',
        ]);

        $this->actingAs($dialA)->get(route('requests.inspection.template'))
            ->assertOk()
            ->assertDownload('Inspection Request Template.xlsx');
        $this->actingAs($dealerUser)->get(route('requests.inspection.template'))->assertForbidden();

        // Before inspection: Work Order stage is locked and finishing fails validation
        $this->actingAs($dialA)->get(route('requests.show', $request))
            ->assertOk()
            ->assertSee('aria-label="Set inspection start date"', false)
            ->assertSee('name="inspection_date"', false)
            ->assertSee('name="inspection_end_date"', false)
            ->assertSee('Start Date')
            ->assertDontSee('<label>End Date</label>', false)
            ->assertSee('Set Inspection')
            ->assertDontSee('Inspection Completed')
            ->assertSee('name="inspection_representatives[]"', false)
            ->assertDontSee('I confirm that I downloaded and filled out the Inspection Request Template')
            ->assertSee('Complete the inspection to unlock this stage.');

        $this->actingAs($dialA)->patch(route('requests.inspection.date', $request), [
            'inspection_date' => today()->format('Y-m-d'),
            'inspection_start_time' => '08:00',
            'inspection_representatives' => ['Dial-A Lead'],
        ])->assertRedirect();

        $this->actingAs($dialA)->get(route('requests.show', $request))
            ->assertOk()
            ->assertSee('Download Template')
            ->assertSee('aria-label="Inspection timing progress"', false)
            ->assertSee('Inspection Completed')
            ->assertSee('name="inspection_representatives[]"', false)
            ->assertSee('value="Dial-A Lead"', false)
            ->assertDontSee('Set Inspection');
        $this->actingAs($dialA)->post(route('requests.finish', $request))
            ->assertSessionHasErrors('completion');

        // The filled workbook is mandatory.
        $this->actingAs($dialA)->patch(route('requests.inspection.complete', $request), [
            'inspection_date' => today()->format('Y-m-d'),
            'inspection_representatives' => ['Lead Technician'],
        ])->assertSessionHasErrors(['inspection_file']);

        // Invalid file extension is rejected
        $this->actingAs($dialA)->patch(route('requests.inspection.complete', $request), [
            'inspection_date' => today()->format('Y-m-d'),
            'inspection_representatives' => ['Lead Technician'],
            'inspection_file' => UploadedFile::fake()->create('inspection.zip', 100, 'application/zip'),
        ])->assertSessionHasErrors('inspection_file');

        // The end time must follow the start time before the inspection can complete.
        $this->actingAs($dialA)->patch(route('requests.inspection.complete', $request), [
            'inspection_date' => today()->format('Y-m-d'),
            'inspection_start_time' => '10:00',
            'inspection_end_time' => '09:00',
            'inspection_representatives' => ['Lead Technician'],
            'inspection_file' => $this->fakeImage('inspection-time.png'),
        ])->assertSessionHasErrors('inspection_end_time');

        // Dial-A completes inspection -> put to In Progress (accepting images and templates)
        $this->actingAs($dialA)->patch(route('requests.inspection.complete', $request), [
            'inspection_date' => today()->format('Y-m-d'),
            'inspection_start_time' => '08:00',
            'inspection_end_time' => '09:00',
            'inspection_representatives' => ['Lead Technician', 'Branch Representative'],
            'inspection_files' => [
                $this->fakeImage('inspection-photo.png'),
                UploadedFile::fake()->create('completed-inspection.xlsx', 100, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
            ],
        ])->assertRedirect()->assertSessionHas('status', 'Inspection marked as completed. The Work Order stage is now available for Dial-A.');

        $request->refresh();
        $this->assertContains($request->status, ['in_progress', 'on_going']);
        $this->assertNotNull($request->inspection_completed_at);
        $this->assertSame('08:00', substr($request->inspection_start_time, 0, 5));
        $this->assertSame('09:00', substr($request->inspection_end_time, 0, 5));
        $this->assertSame('Lead Technician', $request->representative_1);
        $this->assertSame('Branch Representative', $request->representative_2);
        $this->assertDatabaseHas('request_attachments', [
            'property_request_id' => $request->id,
            'category' => 'inspection',
            'original_name' => 'inspection-photo.png',
        ]);
        $this->assertDatabaseHas('request_attachments', [
            'property_request_id' => $request->id,
            'category' => 'inspection',
            'original_name' => 'completed-inspection.xlsx',
        ]);

        // Finishing is still blocked because Work Order and Service Report are required
        $this->actingAs($dialA)->post(route('requests.finish', $request))
            ->assertSessionHasErrors('completion');

        $this->actingAs($dialA)->get(route('requests.show', $request))
            ->assertOk()
            ->assertSee('Work Order Start Date')
            ->assertDontSee('<label>Work Order End Date</label>', false)
            ->assertSee('aria-label="Set Work Order start date"', false)
            ->assertSee('Set Work Order')
            ->assertSee('name="work_order_representatives[]"', false);

        $this->actingAs($dialA)->patch(route('requests.work-order.date', $request), [
            'work_order_start_date' => today()->format('Y-m-d'),
            'work_order_start_time' => '09:00:00',
            'work_order_representatives' => ['Dial-A Lead'],
        ])->assertRedirect();

        $this->actingAs($dialA)->get(route('requests.show', $request))
            ->assertOk()
            ->assertSee('aria-label="Work Order date progress"', false)
            ->assertSee('Done Work Order')
            ->assertSee('name="work_order_representatives[]"', false)
            ->assertSee('value="Dial-A Lead"', false);

        // Upload Work Order
        $this->actingAs($dialA)->post(route('requests.work-order.complete', $request), [
            'work_order_files' => [$this->fakeImage('wo.png')],
        ])->assertSessionHasErrors(['work_order_start_date', 'work_order_end_date', 'work_order_representatives']);

        $this->actingAs($dialA)->post(route('requests.work-order.complete', $request), [
            'work_order_start_date' => today()->format('Y-m-d'),
            'work_order_end_date' => today()->subDay()->format('Y-m-d'),
            'work_order_representatives' => ['Dial-A Lead'],
            'work_order_files' => [$this->fakeImage('invalid-work-order.png')],
        ])->assertSessionHasErrors('work_order_end_date');

        $this->actingAs($dialA)->post(route('requests.work-order.complete', $request), [
            'work_order_start_date' => today()->format('Y-m-d'),
            'work_order_end_date' => today()->format('Y-m-d'),
            'work_order_representatives' => ['Dial-A Lead', 'Worksite Representative'],
            'work_order_files' => [$this->fakeImage('wo.png')],
        ])->assertRedirect();

        $request->refresh();
        $this->assertNotNull($request->work_order_completed_at);
        $this->assertSame(today()->format('Y-m-d'), $request->work_order_start_date?->format('Y-m-d'));
        $this->assertSame(today()->format('Y-m-d'), $request->work_order_end_date?->format('Y-m-d'));
        $this->assertSame(['Dial-A Lead', 'Worksite Representative'], $request->work_order_representatives);

        // Finishing is still blocked because Service Report is required
        $this->actingAs($dialA)->post(route('requests.finish', $request))
            ->assertSessionHasErrors('completion');

        // Upload Service Report
        $this->actingAs($dialA)->post(route('requests.service-report.complete', $request), [
            'service_report_files' => [$this->fakeImage('sr.png')],
        ])->assertRedirect();

        $request->refresh();
        $this->assertNotNull($request->service_report_completed_at);
        $this->assertSame('completed', $request->status);
        $this->assertNotNull($request->completed_at);

        // Stage 4 is now completed without separate finish button
        $this->actingAs($dialA)->get(route('requests.show', $request))
            ->assertOk()
            ->assertSee('Request Completed')
            ->assertDontSee('Confirm &amp; Finish Request', false);

        // Confirming completion marks the request completed
        $this->actingAs($dialA)->post(route('requests.finish', $request))
            ->assertRedirect()
            ->assertSessionHas('status', 'Request successfully confirmed and marked as Completed.');

        $request->refresh();
        $this->assertSame('completed', $request->status);
        $this->assertNotNull($request->completed_at);

        // Fully completed view shows completed stage
        $this->actingAs($dialA)->get(route('requests.show', $request))
            ->assertOk()
            ->assertSee('Request Completed')
            ->assertSee('Service Report completed and request closed by Dial-A.');
    }

    public function test_only_administrator_can_undo_each_workflow_step_and_later_steps_are_reopened(): void
    {
        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer']);
        $dialA = User::factory()->create(['role' => 'dial_a', 'name' => 'Assigned Dial-A']);
        $otherDialA = User::factory()->create(['role' => 'dial_a', 'name' => 'Other Dial-A']);
        $manager = User::factory()->create(['role' => 'pm_manager']);
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'System Administrator']);
        $propertyRequest = $this->propertyRequest($dealer, $dealerUser);

        $propertyRequest->update([
            'assigned_support_id' => $dialA->id,
            'inspection_date' => today(),
            'representative_1' => 'Inspector One',
            'inspection_completed_at' => now()->subDays(3),
            'work_order_start_date' => today()->subDays(2),
            'work_order_representatives' => ['Technician One'],
            'work_order_completed_at' => now()->subDays(2),
            'service_report_completed_at' => now()->subDay(),
            'completion_notified_at' => now()->subHours(2),
            'completed_at' => now()->subHour(),
            'status' => 'completed',
        ]);
        $propertyRequest->attachments()->create([
            'category' => 'service_report',
            'path' => 'request-attachments/service_report/retained.pdf',
            'original_name' => 'retained.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
        ]);

        $this->actingAs($dialA)->get(route('requests.show', $propertyRequest))
            ->assertOk()
            ->assertDontSee('Undo Inspection')
            ->assertDontSee('Undo Work Order')
            ->assertDontSee('Undo Service Report')
            ->assertDontSee('Undo Completion');

        $this->actingAs($admin)->get(route('requests.show', $propertyRequest))
            ->assertOk()
            ->assertSee('Administrator workflow controls')
            ->assertSee('Undo Inspection')
            ->assertSee('Undo Work Order')
            ->assertSee('Undo Service Report')
            ->assertSee('Undo Completion');

        $this->actingAs($otherDialA)->get(route('requests.show', $propertyRequest))
            ->assertOk()
            ->assertDontSee('Undo Inspection');
        $this->actingAs($otherDialA)
            ->patch(route('requests.workflow.undo', [$propertyRequest, 'completion']))
            ->assertForbidden();
        $this->actingAs($dialA)
            ->patch(route('requests.workflow.undo', [$propertyRequest, 'completion']))
            ->assertForbidden();
        $this->actingAs($manager)
            ->patch(route('requests.workflow.undo', [$propertyRequest, 'completion']))
            ->assertForbidden();

        $this->actingAs($admin)
            ->patch(route('requests.workflow.undo', [$propertyRequest, 'completion']), ['current_password' => 'password'])
            ->assertRedirect()
            ->assertSessionHas('status', 'Request Completion was undone. Uploaded files and entered details were kept.');

        $propertyRequest->refresh();
        $this->assertContains($propertyRequest->status, ['in_progress', 'on_going']);
        $this->assertNull($propertyRequest->completed_at);
        $this->assertNotNull($propertyRequest->service_report_completed_at);

        $propertyRequest->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        $this->actingAs($admin)
            ->patch(route('requests.workflow.undo', [$propertyRequest, 'service-report']), ['current_password' => 'password'])
            ->assertRedirect();

        $propertyRequest->refresh();
        $this->assertContains($propertyRequest->status, ['in_progress', 'on_going']);
        $this->assertNull($propertyRequest->service_report_completed_at);
        $this->assertNull($propertyRequest->completion_notified_at);
        $this->assertNull($propertyRequest->completed_at);
        $this->assertNotNull($propertyRequest->work_order_completed_at);

        $propertyRequest->update([
            'service_report_completed_at' => now(),
            'completion_notified_at' => now(),
            'completed_at' => now(),
            'status' => 'completed',
        ]);
        $this->actingAs($admin)
            ->patch(route('requests.workflow.undo', [$propertyRequest, 'work-order']), ['current_password' => 'password'])
            ->assertRedirect();

        $propertyRequest->refresh();
        $this->assertContains($propertyRequest->status, ['in_progress', 'on_going']);
        $this->assertNotNull($propertyRequest->inspection_completed_at);
        $this->assertNull($propertyRequest->work_order_completed_at);
        $this->assertNull($propertyRequest->service_report_completed_at);
        $this->assertNull($propertyRequest->completed_at);

        $propertyRequest->update([
            'work_order_completed_at' => now(),
            'service_report_completed_at' => now(),
            'completion_notified_at' => now(),
            'completed_at' => now(),
            'status' => 'completed',
        ]);
        $this->actingAs($admin)
            ->patch(route('requests.workflow.undo', [$propertyRequest, 'inspection']), ['current_password' => 'password'])
            ->assertRedirect();

        $propertyRequest->refresh();
        $this->assertSame('pending', $propertyRequest->status);
        $this->assertNull($propertyRequest->inspection_completed_at);
        $this->assertNull($propertyRequest->work_order_completed_at);
        $this->assertNull($propertyRequest->service_report_completed_at);
        $this->assertNull($propertyRequest->completed_at);
        $this->assertSame('Inspector One', $propertyRequest->representative_1);
        $this->assertSame(['Technician One'], $propertyRequest->work_order_representatives);
        $this->assertDatabaseHas('request_attachments', [
            'property_request_id' => $propertyRequest->id,
            'category' => 'service_report',
            'original_name' => 'retained.pdf',
        ]);
        $this->assertSame(4, AuditLog::where('action', 'workflow_step_undone')->count());

        $this->actingAs($admin)
            ->patch(route('requests.workflow.undo', [$propertyRequest, 'inspection']), ['current_password' => 'password'])
            ->assertSessionHasErrors('workflow');
    }

    public function test_inspection_and_work_order_use_two_date_activity_progress_flow(): void
    {
        $dealer = $this->dealer();
        $dialA = User::create([
            'name' => 'Dial-A Inspector',
            'email' => 'dial.inspector@example.com',
            'password' => bcrypt('secret123'),
            'role' => 'dial_a',
            'designation' => 'Dial-A Lead',
        ]);
        $dealerUser = User::create([
            'name' => 'Dealer Staff',
            'email' => 'staff@dealer.com',
            'password' => bcrypt('secret123'),
            'role' => 'dealer',
            'dealer_id' => $dealer->id,
            'branch' => $dealer->name,
        ]);

        $request = PropertyRequest::create([
            'reference_no' => 'GPM-20260916-0101',
            'dealer_id' => $dealer->id,
            'submitted_by' => $dealerUser->id,
            'assigned_support_id' => $dialA->id,
            'submitter_name' => $dealerUser->name,
            'designation' => 'Staff',
            'branch' => $dealer->name,
            'area' => $dealer->area,
            'request_type' => 'Airconditioning',
            'priority' => 'regular',
            'description' => 'AC unit leaking water.',
            'request_date' => today(),
            'due_date' => today()->addDays(5),
            'status' => 'pending',
        ]);

        // Non-Dial-A or unassigned cannot set date
        $this->actingAs($dealerUser)->patch(route('requests.inspection.date', $request), [
            'inspection_date' => today()->format('Y-m-d'),
        ])->assertForbidden();

        // 1. Dial-A sets Start Date -> puts status into in_progress ("when start dated, it should put the status into work in progress in the inspection")
        $this->actingAs($dialA)->patch(route('requests.inspection.date', $request), [
            'inspection_date' => today()->format('Y-m-d'),
            'inspection_representatives' => ['Dial-A Inspector', 'Branch Manager'],
        ])->assertRedirect()->assertSessionHas('status');

        $this->travel(1)->minutes();

        $request->refresh();
        $this->assertSame(today()->format('Y-m-d'), $request->inspection_date->format('Y-m-d'));
        $this->assertContains($request->status, ['in_progress', 'on_going']);
        $this->assertNull($request->inspection_completed_at);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'inspection_date_set',
            'subject_id' => $request->id,
        ]);

        // 2. View page shows the saved start date without displaying the automatic end-date fields.
        $this->actingAs($dialA)->get(route('requests.show', $request))
            ->assertOk()
            ->assertSee('On-going')
            ->assertSee('Start Date')
            ->assertDontSee('<label>End Date</label>', false)
            ->assertSee('Inspection Completed')
            ->assertDontSee('activity-timing-stepper');

        // 3. Dial-A completes inspection with Start Date, representatives, and files; End Date and Time auto-recorded
        $this->actingAs($dialA)->patch(route('requests.inspection.complete', $request), [
            'inspection_date' => today()->format('Y-m-d'),
            'inspection_representatives' => ['Dial-A Inspector', 'Branch Manager'],
            'inspection_files' => [$this->fakeImage('onsite-inspection.jpg')],
        ])->assertRedirect()->assertSessionHas('status');

        $request->refresh();
        $this->assertNotNull($request->inspection_completed_at);
        $this->assertSame(today()->format('Y-m-d'), $request->inspection_date->format('Y-m-d'));
        $this->assertSame(today()->format('Y-m-d'), $request->inspection_end_date->format('Y-m-d'));
        $this->assertNotNull($request->inspection_end_time);
        $this->assertSame('Dial-A Inspector', $request->representative_1);
        $this->assertSame('Branch Manager', $request->representative_2);

        // 4. View page shows completed inspection summary with Start Date & End Date, and Work Order stage displays inherited inspection completion as start date & time
        $this->actingAs($dialA)->get(route('requests.show', $request))
            ->assertOk()
            ->assertSee('Start Date')
            ->assertSee('End Date')
            ->assertSee('work_order_start_date')
            ->assertSee('work_order_end_date')
            ->assertSee('Inherited from completed Inspection')
            ->assertSee('Set Work Order')
            ->assertSee('name="work_order_representatives[]"', false)
            ->assertDontSee('activity-timing-stepper')
            ->assertDontSee('data-stage-timing-disclosure');

        $this->actingAs($dialA)->patch(route('requests.work-order.date', $request), [
            'work_order_start_date' => today()->format('Y-m-d'),
            'work_order_representatives' => ['Dial-A Tech'],
        ])->assertRedirect()->assertSessionHas('status');

        $this->travel(1)->minutes();

        $this->actingAs($dialA)->get(route('requests.show', $request))
            ->assertOk()
            ->assertSee('Done Work Order')
            ->assertDontSee('Set Work Order');

        // 5. Complete Work Order -> End Date & Time auto-recorded
        $this->actingAs($dialA)->post(route('requests.work-order.complete', $request), [
            'work_order_start_date' => today()->format('Y-m-d'),
            'work_order_end_date' => today()->format('Y-m-d'),
            'work_order_representatives' => ['Dial-A Tech'],
            'work_order_files' => [$this->fakeImage('work-done.png')],
        ])->assertRedirect()->assertSessionHas('status');

        $request->refresh();
        $this->assertNotNull($request->work_order_completed_at);
        $this->assertSame(today()->format('Y-m-d'), $request->work_order_start_date->format('Y-m-d'));
        $this->assertSame(today()->format('Y-m-d'), $request->work_order_end_date->format('Y-m-d'));
        $this->assertNotNull($request->work_order_end_time);
    }

    public function test_inspection_completion_requires_one_full_minute_after_the_saved_start_timestamp(): void
    {
        Storage::fake('local');
        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['role' => 'dealer', 'dealer_id' => $dealer->id]);
        $dialA = User::factory()->create(['role' => 'dial_a']);
        $request = $this->propertyRequest($dealer, $dealerUser);
        $request->update([
            'assigned_support_id' => $dialA->id,
            'inspection_date' => '2026-09-17',
            'inspection_start_time' => '10:00:00',
            'representative_1' => 'Inspector',
        ]);

        $this->travelTo(\Illuminate\Support\Carbon::parse('2026-09-17 10:00:59'));
        $this->actingAs($dialA)->patch(route('requests.inspection.complete', $request), [
            'inspection_date' => '2026-09-17',
            'inspection_end_date' => '2026-09-17',
            'inspection_end_time' => '10:01',
            'inspection_representatives' => ['Inspector'],
            'inspection_files' => [$this->fakeImage('too-early-inspection.png')],
        ])->assertSessionHasErrors('inspection_end_time');
        $this->assertNull($request->fresh()->inspection_completed_at);

        $this->travelTo(\Illuminate\Support\Carbon::parse('2026-09-17 10:01:00'));
        $this->actingAs($dialA)->patch(route('requests.inspection.complete', $request), [
            'inspection_date' => '2026-09-17',
            'inspection_end_date' => '2026-09-17',
            'inspection_end_time' => '10:01',
            'inspection_representatives' => ['Inspector'],
            'inspection_files' => [$this->fakeImage('valid-inspection.png')],
        ])->assertSessionHasNoErrors();
        $this->assertNotNull($request->fresh()->inspection_completed_at);
    }

    public function test_work_order_completion_requires_one_full_minute_after_the_saved_start_timestamp(): void
    {
        Storage::fake('local');
        $dealer = $this->dealer();
        $dealerUser = User::factory()->create(['role' => 'dealer', 'dealer_id' => $dealer->id]);
        $dialA = User::factory()->create(['role' => 'dial_a']);
        $request = $this->propertyRequest($dealer, $dealerUser);
        $request->update([
            'assigned_support_id' => $dialA->id,
            'inspection_completed_at' => '2026-09-17 09:00:00',
            'work_order_start_date' => '2026-09-17',
            'work_order_start_time' => '10:00:30',
            'work_order_representatives' => ['Technician'],
        ]);

        $this->travelTo(\Illuminate\Support\Carbon::parse('2026-09-17 10:01:29'));
        $this->actingAs($dialA)->post(route('requests.work-order.complete', $request), [
            'work_order_start_date' => '2026-09-17',
            'work_order_end_date' => '2026-09-17',
            'work_order_end_time' => '10:01:29',
            'work_order_representatives' => ['Technician'],
            'work_order_files' => [$this->fakeImage('too-early-work-order.png')],
        ])->assertSessionHasErrors('work_order_end_time');
        $this->assertNull($request->fresh()->work_order_completed_at);

        $this->travelTo(\Illuminate\Support\Carbon::parse('2026-09-17 10:01:30'));
        $this->actingAs($dialA)->post(route('requests.work-order.complete', $request), [
            'work_order_start_date' => '2026-09-17',
            'work_order_end_date' => '2026-09-17',
            'work_order_end_time' => '10:01:30',
            'work_order_representatives' => ['Technician'],
            'work_order_files' => [$this->fakeImage('valid-work-order.png')],
        ])->assertSessionHasNoErrors();
        $this->assertNotNull($request->fresh()->work_order_completed_at);
    }

    private function dealer(): Dealer
    {
        return Dealer::create([
            'source_no' => 1,
            'name' => 'Mitsubishi Pasig',
            'address' => 'Pasig City',
            'area' => 'Metro Manila',
            'brand' => 'Mitsubishi',
        ]);
    }

    private function propertyRequest(Dealer $dealer, User $submitter): PropertyRequest
    {
        return PropertyRequest::create([
            'reference_no' => 'GPM-20260915-0001',
            'dealer_id' => $dealer->id,
            'submitted_by' => $submitter->id,
            'submitter_name' => $submitter->name,
            'designation' => 'Dealer Representative',
            'branch' => $dealer->name,
            'area' => $dealer->area,
            'request_type' => 'Electrical Works',
            'priority' => 'regular',
            'description' => 'Test request description for the role workflow.',
            'request_date' => today(),
            'due_date' => today()->addDays(7),
            'status' => 'pending',
        ]);
    }

    private function fakeImage(string $filename = 'test.png'): UploadedFile
    {
        $content = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        return UploadedFile::fake()->createWithContent($filename, $content);
    }
}
