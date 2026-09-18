<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use Database\Seeders\DealerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DialAWorkflowAlertTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DealerSeeder::class);
    }

    private function createRequest(Dealer $dealer, User $submitter, array $overrides = []): PropertyRequest
    {
        return PropertyRequest::create(array_merge([
            'reference_no' => 'GPM-20260917-0001',
            'dealer_id' => $dealer->id,
            'dealer_name' => $dealer->brand ?: $dealer->name,
            'submitted_by' => $submitter->id,
            'submitter_name' => $submitter->name,
            'designation' => 'Dealer Staff',
            'branch' => $dealer->city ?: $dealer->branch,
            'area' => $dealer->area,
            'request_type' => 'Electrical Works',
            'priority' => 'regular',
            'description' => 'Test request description',
            'request_date' => today(),
            'due_date' => today()->addDays(7),
            'status' => 'pending',
        ], $overrides));
    }

    public function test_dial_a_status_alert_appears_on_top_of_inspection_container_when_inspection_task_saved(): void
    {
        $dealer = Dealer::first();
        $dealerUser = User::factory()->create(['role' => 'dealer', 'dealer_id' => $dealer->id]);
        $dialA = User::factory()->create(['role' => 'dial_a']);
        $request = $this->createRequest($dealer, $dealerUser, [
            'assigned_support_id' => $dialA->id,
        ]);

        $response = $this->actingAs($dialA)
            ->withSession(['status' => 'Inspection start date saved. Request is On-going.'])
            ->get(route('requests.show', $request));

        $response->assertOk();
        $html = $response->getContent();

        $alertSnippet = '<div class="alert alert-success workflow-stage-alert">Inspection start date saved. Request is On-going.</div>';
        $inspectionSnippet = '<section id="inspection" class="panel workflow-stage-card">';

        $this->assertStringContainsString($alertSnippet, $html);
        $this->assertStringContainsString($inspectionSnippet, $html);

        $alertPos = strpos($html, $alertSnippet);
        $inspectionPos = strpos($html, $inspectionSnippet);
        $workOrderPos = strpos($html, '<section id="work-order"');

        $this->assertTrue($alertPos < $inspectionPos, 'Alert should appear before inspection container');
        $this->assertTrue($inspectionPos < $workOrderPos, 'Inspection container should appear before work order container');

        // Ensure global alert at the top was suppressed
        $occurrences = substr_count($html, 'Inspection start date saved. Request is On-going.');
        $this->assertSame(1, $occurrences, 'Alert should only appear once on the page');
    }

    public function test_dial_a_status_alert_appears_on_top_of_inspection_container_when_inspection_task_completed(): void
    {
        $dealer = Dealer::first();
        $dealerUser = User::factory()->create(['role' => 'dealer', 'dealer_id' => $dealer->id]);
        $dialA = User::factory()->create(['role' => 'dial_a']);
        $request = $this->createRequest($dealer, $dealerUser, [
            'assigned_support_id' => $dialA->id,
            'inspection_date' => today()->format('Y-m-d'),
            'inspection_completed_at' => now(),
            'status' => 'on_going',
        ]);

        $response = $this->actingAs($dialA)
            ->withSession(['status' => 'Inspection marked as completed. The Work Order stage is now available for Dial-A.'])
            ->get(route('requests.show', $request));

        $response->assertOk();
        $html = $response->getContent();

        $alertSnippet = '<div class="alert alert-success workflow-stage-alert">Inspection marked as completed. The Work Order stage is now available for Dial-A.</div>';
        $inspectionSnippet = '<section id="inspection" class="panel workflow-stage-card">';

        $this->assertStringContainsString($alertSnippet, $html);
        $this->assertStringContainsString($inspectionSnippet, $html);

        $alertPos = strpos($html, $alertSnippet);
        $inspectionPos = strpos($html, $inspectionSnippet);
        $workOrderPos = strpos($html, '<section id="work-order"');

        $this->assertTrue($alertPos < $inspectionPos, 'Notice must be placed on top of finished inspection task');
        $this->assertTrue($inspectionPos < $workOrderPos, 'Inspection container should appear before work order container');

        $occurrences = substr_count($html, 'Inspection marked as completed. The Work Order stage is now available for Dial-A.');
        $this->assertSame(1, $occurrences);
    }

    public function test_dial_a_status_alert_appears_on_top_of_work_order_container_when_work_order_is_completed(): void
    {
        $dealer = Dealer::first();
        $dealerUser = User::factory()->create(['role' => 'dealer', 'dealer_id' => $dealer->id]);
        $dialA = User::factory()->create(['role' => 'dial_a']);
        $request = $this->createRequest($dealer, $dealerUser, [
            'assigned_support_id' => $dialA->id,
            'inspection_date' => today()->format('Y-m-d'),
            'inspection_completed_at' => now(),
            'work_order_start_date' => today()->format('Y-m-d'),
            'work_order_completed_at' => now(),
            'status' => 'on_going',
        ]);

        $response = $this->actingAs($dialA)
            ->withSession(['status' => 'Work Order completed. The Service Report step is now available for Dial-A.'])
            ->get(route('requests.show', $request));

        $response->assertOk();
        $html = $response->getContent();

        $alertSnippet = '<div class="alert alert-success workflow-stage-alert">Work Order completed. The Service Report step is now available for Dial-A.</div>';
        $workOrderSnippet = '<section id="work-order" class="panel workflow-stage-card';

        $this->assertStringContainsString($alertSnippet, $html);
        $this->assertStringContainsString($workOrderSnippet, $html);

        $inspectionPos = strpos($html, '<section id="inspection"');
        $alertPos = strpos($html, $alertSnippet);
        $workOrderPos = strpos($html, $workOrderSnippet);

        $this->assertTrue($inspectionPos < $alertPos, 'Inspection should be above the work order alert');
        $this->assertTrue($alertPos < $workOrderPos, 'Notice must be placed on top of finished work order task');

        $occurrences = substr_count($html, 'Work Order completed. The Service Report step is now available for Dial-A.');
        $this->assertSame(1, $occurrences);
    }

    public function test_dial_a_status_alert_appears_on_top_of_service_report_container_when_service_report_is_completed(): void
    {
        $dealer = Dealer::first();
        $dealerUser = User::factory()->create(['role' => 'dealer', 'dealer_id' => $dealer->id]);
        $dialA = User::factory()->create(['role' => 'dial_a']);
        $request = $this->createRequest($dealer, $dealerUser, [
            'assigned_support_id' => $dialA->id,
            'inspection_date' => today()->format('Y-m-d'),
            'inspection_completed_at' => now(),
            'work_order_start_date' => today()->format('Y-m-d'),
            'work_order_completed_at' => now(),
            'service_report_date' => today()->format('Y-m-d'),
            'service_report_completed_at' => now(),
            'status' => 'completed',
        ]);

        $response = $this->actingAs($dialA)
            ->withSession(['status' => 'Service Report completed and request marked as Completed.'])
            ->get(route('requests.show', $request));

        $response->assertOk();
        $html = $response->getContent();

        $alertSnippet = '<div class="alert alert-success workflow-stage-alert">Service Report completed and request marked as Completed.</div>';
        $serviceReportSnippet = '<section id="service-report" class="panel workflow-stage-card';

        $this->assertStringContainsString($alertSnippet, $html);
        $this->assertStringContainsString($serviceReportSnippet, $html);

        $workOrderPos = strpos($html, '<section id="work-order"');
        $alertPos = strpos($html, $alertSnippet);
        $serviceReportPos = strpos($html, $serviceReportSnippet);

        $this->assertTrue($workOrderPos < $alertPos, 'Work order should be above service report alert');
        $this->assertTrue($alertPos < $serviceReportPos, 'Notice must be placed on top of finished service report task');

        $occurrences = substr_count($html, 'Service Report completed and request marked as Completed.');
        $this->assertSame(1, $occurrences);
    }

    public function test_dial_a_status_alert_appears_on_top_of_completion_container_when_request_is_confirmed(): void
    {
        $dealer = Dealer::first();
        $dealerUser = User::factory()->create(['role' => 'dealer', 'dealer_id' => $dealer->id]);
        $dialA = User::factory()->create(['role' => 'dial_a']);
        $request = $this->createRequest($dealer, $dealerUser, [
            'assigned_support_id' => $dialA->id,
            'inspection_date' => today()->format('Y-m-d'),
            'inspection_completed_at' => now(),
            'work_order_start_date' => today()->format('Y-m-d'),
            'work_order_completed_at' => now(),
            'service_report_date' => today()->format('Y-m-d'),
            'service_report_completed_at' => now(),
            'completed_at' => now(),
            'status' => 'completed',
        ]);

        $response = $this->actingAs($dialA)
            ->withSession(['status' => 'Request successfully confirmed and marked as Completed.'])
            ->get(route('requests.show', $request));

        $response->assertOk();
        $html = $response->getContent();

        $alertSnippet = '<div class="alert alert-success workflow-stage-alert">Request successfully confirmed and marked as Completed.</div>';
        $completionSnippet = '<section id="request-completed" class="panel workflow-stage-card';

        $this->assertStringContainsString($alertSnippet, $html);
        $this->assertStringContainsString($completionSnippet, $html);

        $serviceReportPos = strpos($html, '<section id="service-report"');
        $alertPos = strpos($html, $alertSnippet);
        $completionPos = strpos($html, $completionSnippet);

        $this->assertTrue($serviceReportPos < $alertPos, 'Service report should be above completion alert');
        $this->assertTrue($alertPos < $completionPos, 'Completion alert should appear directly before completion container');

        $occurrences = substr_count($html, 'Request successfully confirmed and marked as Completed.');
        $this->assertSame(1, $occurrences);
    }
}
