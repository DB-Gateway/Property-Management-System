<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use Database\Seeders\DealerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServiceReportTwoStepFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $dialA;
    private User $otherDialA;
    private User $manager;
    private User $dealerUser;
    private Dealer $dealer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DealerSeeder::class);
        Storage::fake('local');

        $this->admin = User::factory()->create(['role' => 'admin', 'name' => 'Admin User']);
        $this->dialA = User::factory()->create(['role' => 'dial_a', 'name' => 'Dial-A Lead']);
        $this->otherDialA = User::factory()->create(['role' => 'dial_a', 'name' => 'Other Dial-A']);
        $this->manager = User::factory()->create(['role' => 'pm_manager', 'name' => 'PM Manager']);
        $this->dealer = Dealer::first();
        $this->dealerUser = User::factory()->create(['role' => 'dealer', 'dealer_id' => $this->dealer->id]);
    }

    private function createWorkOrderCompletedRequest(): PropertyRequest
    {
        return PropertyRequest::create([
            'reference_no' => 'GPM-20260917-0099',
            'dealer_id' => $this->dealer->id,
            'dealer_name' => $this->dealer->brand ?: $this->dealer->name,
            'submitted_by' => $this->dealerUser->id,
            'assigned_support_id' => $this->dialA->id,
            'submitter_name' => $this->dealerUser->name,
            'designation' => 'Branch Staff',
            'branch' => $this->dealer->city ?: $this->dealer->branch,
            'area' => $this->dealer->area,
            'request_type' => 'Electrical Works',
            'priority' => 'regular',
            'description' => 'Test Service Report 2-step flow description.',
            'request_date' => today(),
            'due_date' => today()->addDays(7),
            'status' => 'on_going',
            'inspection_date' => today(),
            'inspection_completed_at' => now(),
            'work_order_start_date' => today(),
            'work_order_completed_at' => now(),
        ]);
    }

    public function test_dial_a_sees_step_1_upload_and_can_upload_attachments(): void
    {
        $request = $this->createWorkOrderCompletedRequest();

        // 1. Dial-A visits the request page: sees Step 1 upload form and mention of Step 2 Done Service Report
        $response = $this->actingAs($this->dialA)->get(route('requests.show', $request));
        $response->assertOk();
        $response->assertSee('Upload Service Report');
        $response->assertSee('Done Service Report');

        // 2. Unauthorized users cannot upload via Step 1
        $file = UploadedFile::fake()->create('service-report.pdf', 500, 'application/pdf');
        $this->actingAs($this->dealerUser)
            ->post(route('requests.service-report.upload', $request), ['service_report_files' => [$file]])
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->post(route('requests.service-report.upload', $request), ['service_report_files' => [$file]])
            ->assertForbidden();

        // 3. Dial-A performs Step 1: uploads the Service Report attachments
        $response = $this->actingAs($this->dialA)
            ->post(route('requests.service-report.upload', $request), [
                'service_report_files' => [$file],
            ]);

        $response->assertRedirect(route('requests.show', $request).'#service-report');
        $response->assertSessionHas('status', 'Service Report attachments uploaded. Please confirm Done Service Report to complete this request.');

        $request->refresh();
        $this->assertSame(1, $request->serviceReportFiles()->count());
        $this->assertNotNull($request->service_report_completed_at);
        $this->assertNull($request->completed_at);
        $this->assertSame('on_going', $request->status);
        $this->assertTrue($request->isAwaitingDialACompletion());
        $this->assertSame('Awaiting Dial-A Confirmation', $request->status_label);
    }

    public function test_dashboard_notification_pops_up_when_request_is_unconfirmed(): void
    {
        $request = $this->createWorkOrderCompletedRequest();

        // Perform Step 1 upload
        $file = UploadedFile::fake()->create('report-doc.docx', 200, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $this->actingAs($this->dialA)
            ->post(route('requests.service-report.upload', $request), [
                'service_report_files' => [$file],
            ])->assertRedirect();

        // Dial-A checks dashboard: the unconfirmed request triggers the notification banner
        $dashboardResponse = $this->actingAs($this->dialA)->get(route('dashboard'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertViewHas('awaitingCompletion', fn ($collection) => $collection->pluck('id')->contains($request->id));
        $dashboardResponse->assertSee('Request(s) Awaiting Final Confirmation');
        $dashboardResponse->assertSee($request->reference_no);
    }

    public function test_dial_a_performs_step_2_done_service_report_which_completes_request_and_clears_notification(): void
    {
        $request = $this->createWorkOrderCompletedRequest();

        // Step 1: upload
        $file = UploadedFile::fake()->create('service-photo.jpg', 300, 'image/jpeg');
        $this->actingAs($this->dialA)
            ->post(route('requests.service-report.upload', $request), [
                'service_report_files' => [$file],
            ])->assertRedirect();

        // Dial-A visits request: sees Step 1 completed, uploaded file, and Step 2 "Done Service Report" button
        $showResponse = $this->actingAs($this->dialA)->get(route('requests.show', $request));
        $showResponse->assertOk();
        $showResponse->assertSee('Step 1 Complete');
        $showResponse->assertSee('service-photo.jpg');
        $showResponse->assertSee('Done Service Report');

        // Step 2: Confirm Done Service Report
        $finishResponse = $this->actingAs($this->dialA)
            ->post(route('requests.finish', $request));

        $finishResponse->assertRedirect();
        $finishResponse->assertSessionHas('status', 'Request successfully confirmed and marked as Completed.');

        $request->refresh();
        $this->assertSame('completed', $request->status);
        $this->assertNotNull($request->completed_at);
        $this->assertNotNull($request->service_report_completed_at);
        $this->assertFalse($request->isAwaitingDialACompletion());

        // Check dashboard: notification for this request is now cleared
        $dashboardResponse = $this->actingAs($this->dialA)->get(route('dashboard'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertViewHas('awaitingCompletion', fn ($collection) => ! $collection->pluck('id')->contains($request->id));
    }
}
