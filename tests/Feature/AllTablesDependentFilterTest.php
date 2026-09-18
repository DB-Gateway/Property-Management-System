<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use Database\Seeders\DealerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllTablesDependentFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DealerSeeder::class);
    }

    public function test_requests_index_cascading_filters_for_pm_manager_and_dial_a(): void
    {
        $manager = User::factory()->create(['role' => 'pm_manager']);
        $dialA = User::factory()->create(['role' => 'dial_a']);

        // Test branch filter for PM Manager
        $response = $this->actingAs($manager)->get(route('requests.index', ['branch' => 'Pasig']));
        $response->assertOk();

        $areas = $response->viewData('areas');
        $brands = $response->viewData('brands');
        $this->assertEquals(['Metro Manila'], $areas->values()->all());
        $this->assertContains('Mitsubishi', $brands);
        $this->assertContains('Honda', $brands);
        $this->assertNotContains('Suzuki', $brands);

        $response->assertSee('id="requests-filter-form"', false);
        $response->assertSee('data-cascading-filter-form', false);
        $response->assertSee('All Areas');
        $response->assertSee('All Dealers');
        $response->assertSee('All Activity');
        $response->assertSee('All Progress');
        $response->assertDontSee('All branches');
        $response->assertDontSee('All stages');
        $response->assertDontSee('All statuses');
        $response->assertDontSee('id="request-filter-branch"', false);
        $response->assertSee('id="request-filter-area"', false);
        $response->assertSee('id="request-filter-brand"', false);
        $response->assertSee('Search Ticket number');
        $response->assertSee('Search Month -');
        $response->assertSee('Search Area');
        $response->assertSee('Search Dealer');
        $response->assertSee('Search Activities');
        $response->assertSee('Search Progress');
        $response->assertSee('filter-icon-box');

        // Test combined city and area filter for PM Manager
        $responseCombined = $this->actingAs($manager)->get(route('requests.index', ['area' => 'Pasig - Metro Manila']));
        $responseCombined->assertOk();
        $responseCombined->assertSee('Pasig - Metro Manila');

        // Test area filter for Dial-A
        $responseDialA = $this->actingAs($dialA)->get(route('requests.index', ['area' => 'North Luzon']));
        $responseDialA->assertOk();

        $cities = $responseDialA->viewData('branches');
        $this->assertContains('Angeles', $cities);
        $this->assertNotContains('Pasig', $cities);
    }

    public function test_requests_index_cascading_filters_for_dealer_user(): void
    {
        $dealer = Dealer::where('city', 'Pasig')->where('brand', 'Mitsubishi')->firstOrFail();
        $dealerUser = User::factory()->create([
            'role' => 'dealer',
            'dealer_id' => $dealer->id,
        ]);

        $response = $this->actingAs($dealerUser)->get(route('requests.index'));
        $response->assertOk();

        $branches = $response->viewData('branches');
        $areas = $response->viewData('areas');

        // Scoped to dealer facility
        $this->assertContains('Pasig', $branches);
        $this->assertNotContains('Malolos', $branches);
        $this->assertEquals(['Metro Manila'], $areas->values()->all());

        // Dealer should not see branch, area, or dealer filters
        $response->assertDontSee('id="request-filter-branch"', false);
        $response->assertDontSee('id="request-filter-area"', false);
        $response->assertDontSee('id="request-filter-brand"', false);
        $response->assertDontSee('All branches');
        $response->assertDontSee('All Areas');
        $response->assertDontSee('All Dealers');
    }

    public function test_reports_index_cascading_filters_for_manager(): void
    {
        $manager = User::factory()->create(['role' => 'pm_manager']);

        $response = $this->actingAs($manager)->get(route('reports.index', ['branch' => 'Pasig']));
        $response->assertOk();

        $areas = $response->viewData('areas');
        $brands = $response->viewData('brands');

        $this->assertEquals(['Metro Manila'], $areas->values()->all());
        $this->assertContains('Mitsubishi', $brands);
        $this->assertContains('Honda', $brands);
        $this->assertNotContains('Geely', $brands);

        $response->assertSee('id="report-filter-form"', false);
        $response->assertSee('data-cascading-filter-form', false);
        $response->assertSee('data-cascading-matrix', false);
    }

    public function test_reports_print_accepts_branch_area_and_brand_filters(): void
    {
        $manager = User::factory()->create(['role' => 'pm_manager']);

        $response = $this->actingAs($manager)->get(route('reports.print', [
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'brand' => 'Mitsubishi',
        ]));

        $response->assertOk();
        $response->assertSee('Completed Requests Operations Report');
    }

    public function test_requests_index_filtering_by_combined_area_and_dealer(): void
    {
        $manager = User::factory()->create(['role' => 'pm_manager']);
        $dealerUser = User::factory()->create(['role' => 'dealer']);

        $pasigDealer = Dealer::where('city', 'Pasig')->where('brand', 'Mitsubishi')->firstOrFail();
        $manilaDealer = Dealer::where('city', 'Manila')->where('brand', 'MG')->firstOrFail();
        $angelesDealer = Dealer::where('city', 'Angeles')->firstOrFail();

        $reqPasig = PropertyRequest::create([
            'reference_no' => 'REQ-TEST-PASIG-001',
            'dealer_id' => $pasigDealer->id,
            'dealer_name' => 'Mitsubishi',
            'submitted_by' => $dealerUser->id,
            'submitter_name' => 'Test Submitter',
            'designation' => 'Staff',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Electrical Works',
            'priority' => 'regular',
            'description' => 'Pasig request',
            'request_date' => today(),
            'due_date' => today()->addDays(7),
            'status' => 'pending',
        ]);

        $reqManila = PropertyRequest::create([
            'reference_no' => 'REQ-TEST-MANILA-002',
            'dealer_id' => $manilaDealer->id,
            'dealer_name' => 'MG',
            'submitted_by' => $dealerUser->id,
            'submitter_name' => 'Test Submitter',
            'designation' => 'Staff',
            'branch' => 'Manila',
            'area' => 'Metro Manila',
            'request_type' => 'General Repairs',
            'priority' => 'regular',
            'description' => 'Manila request',
            'request_date' => today(),
            'due_date' => today()->addDays(7),
            'status' => 'pending',
        ]);

        $reqAngeles = PropertyRequest::create([
            'reference_no' => 'REQ-TEST-ANGELES-003',
            'dealer_id' => $angelesDealer->id,
            'dealer_name' => $angelesDealer->brand ?: $angelesDealer->name,
            'submitted_by' => $dealerUser->id,
            'submitter_name' => 'Test Submitter',
            'designation' => 'Staff',
            'branch' => 'Angeles',
            'area' => 'North Luzon',
            'request_type' => 'Plumbing Works',
            'priority' => 'regular',
            'description' => 'Angeles request',
            'request_date' => today(),
            'due_date' => today()->addDays(7),
            'status' => 'pending',
        ]);

        // Filter by combined city and area
        $respCombined = $this->actingAs($manager)->get(route('requests.index', ['area' => 'Pasig - Metro Manila']));
        $respCombined->assertOk();
        $respCombined->assertSee('REQ-TEST-PASIG-001');
        $respCombined->assertDontSee('REQ-TEST-MANILA-002');
        $respCombined->assertDontSee('REQ-TEST-ANGELES-003');

        // Filter by broad area
        $respArea = $this->actingAs($manager)->get(route('requests.index', ['area' => 'Metro Manila']));
        $respArea->assertOk();
        $respArea->assertSee('REQ-TEST-PASIG-001');
        $respArea->assertSee('REQ-TEST-MANILA-002');
        $respArea->assertDontSee('REQ-TEST-ANGELES-003');

        // Filter by brand (renamed to All Dealers)
        $respBrand = $this->actingAs($manager)->get(route('requests.index', ['brand' => 'Mitsubishi']));
        $respBrand->assertOk();
        $respBrand->assertSee('REQ-TEST-PASIG-001');
        $respBrand->assertDontSee('REQ-TEST-MANILA-002');

        // Filter with 'dealer' alias parameter
        $respDealer = $this->actingAs($manager)->get(route('requests.index', ['dealer' => 'MG']));
        $respDealer->assertOk();
        $respDealer->assertSee('REQ-TEST-MANILA-002');
        $respDealer->assertDontSee('REQ-TEST-PASIG-001');
    }

    public function test_for_acknowledgement_filter_in_activities(): void
    {
        $manager = User::factory()->create(['role' => 'pm_manager']);

        $response = $this->actingAs($manager)->get(route('requests.index'));
        $response->assertOk();

        // Verify "For Acknowledgement" is in request-filter-stage (Search Activities) and "completed" is not
        $html = $response->getContent();
        preg_match('/<select name="stage" id="request-filter-stage"[^>]*>(.*?)<\/select>/s', $html, $stageMatch);
        $this->assertNotEmpty($stageMatch);
        $this->assertStringContainsString('For Acknowledgement', $stageMatch[1]);
        $this->assertStringContainsString('value="not_acknowledged"', $stageMatch[1]);
        $this->assertStringNotContainsString('value="completed"', $stageMatch[1]);

        // Verify "For Acknowledgement" is NOT in request-filter-status (Search Progress) but "completed" is
        preg_match('/<select name="status" id="request-filter-status"[^>]*>(.*?)<\/select>/s', $html, $statusMatch);
        $this->assertNotEmpty($statusMatch);
        $this->assertStringNotContainsString('For Acknowledgement', $statusMatch[1]);
        $this->assertStringContainsString('value="completed"', $statusMatch[1]);

        // Test filtering by stage=not_acknowledged
        $responseFiltered = $this->actingAs($manager)->get(route('requests.index', ['stage' => 'not_acknowledged']));
        $responseFiltered->assertOk();
        $responseFiltered->assertSee('For Acknowledgement');

        // Test filtering by status=not_acknowledged (backward compatibility)
        $responseLegacy = $this->actingAs($manager)->get(route('requests.index', ['status' => 'not_acknowledged']));
        $responseLegacy->assertOk();
        $responseLegacy->assertSee('For Acknowledgement');
    }
}
