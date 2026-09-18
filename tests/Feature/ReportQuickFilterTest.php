<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportQuickFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $manager;

    private User $submitter;

    private Dealer $dealer;

    protected function setUp(): void
    {
        parent::setUp();

        // Pin current time to September 2026 for consistent monthly reporting
        Carbon::setTestNow(Carbon::parse('2026-09-18 10:00:00'));

        $this->manager = User::factory()->create(['role' => 'pm_manager']);
        $this->submitter = User::factory()->create(['role' => 'dealer']);
        $this->dealer = Dealer::create([
            'source_no' => 1,
            'name' => 'Test Dealer',
            'city' => 'Pasig',
            'area' => 'Metro Manila',
            'brand' => 'Test Brand',
        ]);
        $this->submitter->update(['dealer_id' => $this->dealer->id]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_reports_page_renders_quick_filter_attributes_and_stage_dropdown(): void
    {
        $response = $this->actingAs($this->manager)->get(route('reports.index'));

        $response->assertOk()
            ->assertSee('data-report-quick-filter', false)
            ->assertSee('data-quick-stage="inspection"', false)
            ->assertSee('data-quick-status="pending"', false)
            ->assertSee('id="stage"', false)
            ->assertSee('Workflow Stage', false);
    }

    public function test_quick_filter_inspection_pending_filters_requests_and_highlights_filter(): void
    {
        // 1. Inspection pending request (status = pending, inspection_completed_at = null)
        $inspectionPending = PropertyRequest::create([
            'reference_no' => 'GPM-INSP-PENDING',
            'dealer_id' => $this->dealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Rep',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Aircon Repair',
            'priority' => 'regular',
            'description' => 'Pending in inspection',
            'request_date' => '2026-09-10',
            'due_date' => '2026-09-14',
            'status' => 'pending',
            'inspection_completed_at' => null,
        ]);

        // 2. Inspection ongoing request (status = on_going, inspection_completed_at = null)
        $inspectionOngoing = PropertyRequest::create([
            'reference_no' => 'GPM-INSP-ONGOING',
            'dealer_id' => $this->dealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Rep',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Aircon Repair',
            'priority' => 'regular',
            'description' => 'Ongoing in inspection',
            'request_date' => '2026-09-10',
            'due_date' => '2026-09-14',
            'status' => 'on_going',
            'inspection_completed_at' => null,
        ]);

        // 3. Completed request
        $completedReq = PropertyRequest::create([
            'reference_no' => 'GPM-COMPLETED-1',
            'dealer_id' => $this->dealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Rep',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Electrical',
            'priority' => 'urgent',
            'description' => 'Completed request',
            'request_date' => '2026-09-10',
            'due_date' => '2026-09-14',
            'status' => 'completed',
            'completed_at' => '2026-09-12 15:00:00',
        ]);

        $response = $this->actingAs($this->manager)->get(route('reports.index', [
            'stage' => 'inspection',
            'status' => 'pending',
        ]));

        $response->assertOk();
        $response->assertSee('GPM-INSP-PENDING');
        $response->assertDontSee('GPM-INSP-ONGOING');
        $response->assertDontSee('GPM-COMPLETED-1');

        // Check active banner
        $response->assertSee('Quick Filter Active:');
        $response->assertSee('Inspection');
        $response->assertSee('Pending');

        // Check active class on substat link
        $response->assertSee('stage-substat-link substat-pending is-active', false);
    }

    public function test_quick_filter_work_order_ongoing_filters_correctly(): void
    {
        $woOngoing = PropertyRequest::create([
            'reference_no' => 'GPM-WO-ONGOING',
            'dealer_id' => $this->dealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Rep',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Civil Works',
            'priority' => 'regular',
            'description' => 'Ongoing in WO',
            'request_date' => '2026-09-10',
            'due_date' => '2026-09-14',
            'status' => 'on_going',
            'inspection_completed_at' => '2026-09-11 10:00:00',
            'work_order_completed_at' => null,
        ]);

        $other = PropertyRequest::create([
            'reference_no' => 'GPM-OTHER-REQ',
            'dealer_id' => $this->dealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Rep',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Civil Works',
            'priority' => 'regular',
            'description' => 'Pending in inspection',
            'request_date' => '2026-09-10',
            'due_date' => '2026-09-14',
            'status' => 'pending',
            'inspection_completed_at' => null,
        ]);

        $response = $this->actingAs($this->manager)->get(route('reports.index', [
            'stage' => 'work_order',
            'status' => 'on_going',
        ]));

        $response->assertOk();
        $response->assertSee('GPM-WO-ONGOING');
        $response->assertDontSee('GPM-OTHER-REQ');
        $response->assertSee('stage-substat-link substat-ongoing is-active', false);
    }

    public function test_quick_filter_total_requests_shows_all_statuses(): void
    {
        $pending = PropertyRequest::create([
            'reference_no' => 'GPM-TOTAL-PENDING',
            'dealer_id' => $this->dealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Rep',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Civil Works',
            'priority' => 'regular',
            'description' => 'Pending request',
            'request_date' => '2026-09-10',
            'due_date' => '2026-09-14',
            'status' => 'pending',
        ]);

        $completed = PropertyRequest::create([
            'reference_no' => 'GPM-TOTAL-COMPLETED',
            'dealer_id' => $this->dealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Rep',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Civil Works',
            'priority' => 'regular',
            'description' => 'Completed request',
            'request_date' => '2026-09-10',
            'due_date' => '2026-09-14',
            'status' => 'completed',
            'completed_at' => '2026-09-12 12:00:00',
        ]);

        $response = $this->actingAs($this->manager)->get(route('reports.index', [
            'status' => 'all',
        ]));

        $response->assertOk();
        $response->assertSee('GPM-TOTAL-PENDING');
        $response->assertSee('GPM-TOTAL-COMPLETED');
    }

    public function test_quick_filter_not_acknowledged_filters_and_highlights_card(): void
    {
        $notAck = PropertyRequest::create([
            'reference_no' => 'GPM-NOT-ACK',
            'dealer_id' => $this->dealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Rep',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Civil Works',
            'priority' => 'regular',
            'description' => 'Awaiting acknowledgement',
            'request_date' => '2026-09-10',
            'due_date' => '2026-09-14',
            'status' => 'pending',
            'inspection_completed_at' => null,
            'work_order_completed_at' => null,
            'service_report_completed_at' => null,
        ]);

        $response = $this->actingAs($this->manager)->get(route('reports.index', [
            'status' => 'not_acknowledged',
        ]));

        $response->assertOk();
        $response->assertSee('GPM-NOT-ACK');
        $response->assertSee('stat-card stat-orange is-active', false);
        $response->assertSee('Quick Filter Active:');
        $response->assertSee('For Acknowledgement');
    }

    public function test_printable_report_displays_active_stage_and_status_filters(): void
    {
        $inspectionPending = PropertyRequest::create([
            'reference_no' => 'GPM-PRINT-TEST',
            'dealer_id' => $this->dealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Rep',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Aircon Repair',
            'priority' => 'regular',
            'description' => 'Inspection pending for print',
            'request_date' => '2026-09-10',
            'due_date' => '2026-09-14',
            'status' => 'pending',
            'inspection_completed_at' => null,
        ]);

        $response = $this->actingAs($this->manager)->get(route('reports.print', [
            'stage' => 'inspection',
            'status' => 'pending',
        ]));

        $response->assertOk();
        $response->assertSee('GPM-PRINT-TEST');
        $response->assertSee('Stage: Inspection');
        $response->assertSee('Status: Pending');
    }

    public function test_reports_page_does_not_contain_all_support_filter(): void
    {
        $response = $this->actingAs($this->manager)->get(route('reports.index'));

        $response->assertOk();
        $response->assertDontSee('All Support');
        $response->assertDontSee('rfg-support');
        $response->assertDontSee('name="support"', false);
    }

    public function test_reports_page_combines_branches_and_areas_in_one_filter(): void
    {
        $response = $this->actingAs($this->manager)->get(route('reports.index'));

        $response->assertOk();
        $response->assertSee('data-cascading="combined-area"', false);
        $response->assertSee('rfg-area');
        $response->assertDontSee('rfg-branch');
        $response->assertSee('Pasig - Metro Manila');
        $response->assertSee('Metro Manila (All)');
    }

    public function test_reports_page_filters_by_combined_area(): void
    {
        $pasigReq = PropertyRequest::create([
            'reference_no' => 'GPM-PASIG-1',
            'dealer_id' => $this->dealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Rep',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Aircon Repair',
            'priority' => 'regular',
            'description' => 'Pasig request',
            'request_date' => '2026-09-10',
            'due_date' => '2026-09-14',
            'status' => 'completed',
            'completed_at' => '2026-09-12 10:00:00',
        ]);

        $cebuDealer = Dealer::create([
            'source_no' => 2,
            'name' => 'Cebu Dealer',
            'city' => 'Cebu City',
            'area' => 'Visayas',
            'brand' => 'Test Brand 2',
        ]);

        $cebuReq = PropertyRequest::create([
            'reference_no' => 'GPM-CEBU-1',
            'dealer_id' => $cebuDealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Rep',
            'branch' => 'Cebu City',
            'area' => 'Visayas',
            'request_type' => 'Civil Works',
            'priority' => 'urgent',
            'description' => 'Cebu request',
            'request_date' => '2026-09-10',
            'due_date' => '2026-09-14',
            'status' => 'completed',
            'completed_at' => '2026-09-12 11:00:00',
        ]);

        $response = $this->actingAs($this->manager)->get(route('reports.index', [
            'area' => 'Pasig - Metro Manila',
        ]));

        $response->assertOk();
        $response->assertSee('GPM-PASIG-1');
        $response->assertDontSee('GPM-CEBU-1');
        $response->assertSee('rfg-field rfg-area is-active', false);
    }

    public function test_requests_index_highlights_active_filters_for_dealer_without_unauthorized_filters(): void
    {
        $response = $this->actingAs($this->submitter)->get(route('requests.index', [
            'priority' => 'urgent',
        ]));

        $response->assertOk();
        // Priority filter must be highlighted
        $response->assertSee('filter-field filter-field-priority is-active', false);
        // Reset button must indicate active filters
        $response->assertSee('button button-light has-active', false);
        // Dealer MUST NOT see area or dealer/brand filters
        $response->assertDontSee('request-filter-area');
        $response->assertDontSee('request-filter-brand');
        $response->assertDontSee('filter-field-area');
        $response->assertDontSee('filter-field-dealer');
    }

    public function test_requests_index_highlights_active_filters_for_dial_a_including_area_and_brand(): void
    {
        $dialA = User::factory()->create(['role' => 'dial_a']);

        $response = $this->actingAs($dialA)->get(route('requests.index', [
            'area' => 'Pasig - Metro Manila',
            'brand' => 'Test Brand',
        ]));

        $response->assertOk();
        // Dial-A MUST see area and brand filters
        $response->assertSee('request-filter-area');
        $response->assertSee('request-filter-brand');
        // Both area and brand should be highlighted as active
        $response->assertSee('filter-field filter-field-area is-active', false);
        $response->assertSee('filter-field filter-field-dealer is-active', false);
        // Reset button must indicate active filters
        $response->assertSee('button button-light has-active', false);
    }

    public function test_dealers_index_highlights_active_filters_and_reset_button(): void
    {
        $response = $this->actingAs($this->manager)->get(route('dealers.index', [
            'search' => 'Test',
            'brand' => 'Test Brand',
        ]));

        $response->assertOk();
        $response->assertSee('filter-field filter-field-search is-active', false);
        $response->assertSee('filter-field filter-field-brand is-active', false);
        $response->assertSee('button button-light has-active', false);
    }

    public function test_requests_index_renders_dashboard_stats_and_completed_card(): void
    {
        $response = $this->actingAs($this->manager)->get(route('requests.index'));

        $response->assertOk();
        $response->assertSee('stat-column-dual', false);
        $response->assertSee('Total Requests');
        $response->assertSee('Completed');
        $response->assertSee('data-quick-status="completed"', false);
        $response->assertSee('data-report-quick-filter', false);
    }

    public function test_requests_index_quick_filter_completed_filters_and_highlights_card(): void
    {
        $pending = PropertyRequest::create([
            'reference_no' => 'GPM-REQ-PENDING',
            'dealer_id' => $this->dealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Rep',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Aircon Repair',
            'priority' => 'regular',
            'description' => 'Pending request',
            'request_date' => '2026-09-10',
            'due_date' => '2026-09-14',
            'status' => 'pending',
        ]);

        $completed = PropertyRequest::create([
            'reference_no' => 'GPM-REQ-COMPLETED',
            'dealer_id' => $this->dealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Rep',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Aircon Repair',
            'priority' => 'regular',
            'description' => 'Completed request',
            'request_date' => '2026-09-10',
            'due_date' => '2026-09-14',
            'status' => 'completed',
            'completed_at' => '2026-09-12 12:00:00',
        ]);

        $response = $this->actingAs($this->manager)->get(route('requests.index', [
            'status' => 'completed',
        ]));

        $response->assertOk();
        $response->assertSee('GPM-REQ-COMPLETED');
        $response->assertDontSee('GPM-REQ-PENDING');
        $response->assertSee('stat-card stat-green stat-card-dual is-active', false);
    }

    public function test_requests_index_renders_dashboard_stats_for_all_user_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $dialA = User::factory()->create(['role' => 'dial_a']);

        foreach ([$this->submitter, $dialA, $this->manager, $admin] as $user) {
            $response = $this->actingAs($user)->get(route('requests.index'));
            $response->assertOk();
            $response->assertSee('id="dashboard-stats-grid"', false);
            $response->assertSee('Total Requests');
            $response->assertSee('Inspection');
            $response->assertSee('Work Order');
            $response->assertSee('Service Report');
            $response->assertSee('Completed');
        }
    }
}



