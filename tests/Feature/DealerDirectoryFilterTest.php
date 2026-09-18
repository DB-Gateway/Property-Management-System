<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\User;
use Database\Seeders\DealerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DealerDirectoryFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DealerSeeder::class);
    }

    public function test_pm_manager_and_dial_a_can_access_dealer_directory(): void
    {
        $manager = User::factory()->create(['role' => 'pm_manager']);
        $dialA = User::factory()->create(['role' => 'dial_a']);
        $dealerUser = User::factory()->create(['role' => 'dealer']);

        $this->actingAs($manager)->get(route('dealers.index'))->assertOk();
        $this->actingAs($dialA)->get(route('dealers.index'))->assertOk();
        $this->actingAs($dealerUser)->get(route('dealers.index'))->assertForbidden();
    }

    public function test_picking_a_branch_strictly_restricts_available_brands_and_area(): void
    {
        $manager = User::factory()->create(['role' => 'pm_manager']);

        // Pasig is in Metro Manila and only has Mitsubishi and Honda in Luzon directory
        $response = $this->actingAs($manager)->get(route('dealers.index', ['city' => 'Pasig']));
        $response->assertOk();

        $areas = $response->viewData('areas');
        $brands = $response->viewData('brands');
        $directoryMatrix = $response->viewData('directoryMatrix');

        $this->assertEquals(['Metro Manila'], $areas->values()->all());
        $this->assertContains('Mitsubishi', $brands);
        $this->assertContains('Honda', $brands);
        $this->assertNotContains('Suzuki', $brands);
        $this->assertNotContains('Geely', $brands);
        $this->assertNotEmpty($directoryMatrix);

        $response->assertSee('id="dealer-directory-data"', false);
        $response->assertSee('dealer-filter-city', false);
        $response->assertSee('dealer-filter-area', false);
        $response->assertSee('dealer-filter-brand', false);
    }

    public function test_picking_an_area_strictly_restricts_available_branches_and_brands(): void
    {
        $dialA = User::factory()->create(['role' => 'dial_a']);

        $response = $this->actingAs($dialA)->get(route('dealers.index', ['area' => 'North Luzon']));
        $response->assertOk();

        $cities = $response->viewData('cities');
        $brands = $response->viewData('brands');

        // North Luzon cities include Malolos, Cauayan, Angeles, etc., but NOT Pasig or Makati
        $this->assertContains('Angeles', $cities);
        $this->assertContains('Cauayan', $cities);
        $this->assertNotContains('Pasig', $cities);
        $this->assertNotContains('Makati', $cities);

        // North Luzon brands
        $this->assertContains('Geely', $brands);
        $this->assertContains('MG', $brands);
        $this->assertContains('Changan', $brands);
        $this->assertNotContains('Suzuki', $brands);
    }

    public function test_picking_a_brand_strictly_restricts_available_branches_and_areas(): void
    {
        $manager = User::factory()->create(['role' => 'pm_manager']);

        // Changan in data is only in Angeles, Bacoor, Batangas
        $response = $this->actingAs($manager)->get(route('dealers.index', ['brand' => 'Changan']));
        $response->assertOk();

        $cities = $response->viewData('cities');
        $areas = $response->viewData('areas');

        $this->assertContains('Angeles', $cities);
        $this->assertContains('Bacoor', $cities);
        $this->assertContains('Batangas', $cities);
        $this->assertNotContains('Pasig', $cities);
        $this->assertNotContains('Manila', $cities);

        $this->assertContains('North Luzon', $areas);
        $this->assertContains('Metro Manila', $areas);
        $this->assertContains('South Luzon', $areas);
    }

    public function test_multi_filter_combination_and_graceful_sanitization(): void
    {
        $manager = User::factory()->create(['role' => 'pm_manager']);

        // Angeles + Changan
        $response = $this->actingAs($manager)->get(route('dealers.index', [
            'city' => 'Angeles',
            'brand' => 'Changan',
        ]));
        $response->assertOk();

        $areas = $response->viewData('areas');
        $this->assertEquals(['North Luzon'], $areas->values()->all());

        // Incompatible filter: Pasig (Metro Manila) + North Luzon should sanitize without breaking
        $responseIncompatible = $this->actingAs($manager)->get(route('dealers.index', [
            'city' => 'Pasig',
            'area' => 'North Luzon',
        ]));
        $responseIncompatible->assertOk();
    }

    public function test_dealer_filter_form_renders_icons_labels_and_combined_area(): void
    {
        $manager = User::factory()->create(['role' => 'pm_manager']);

        $response = $this->actingAs($manager)->get(route('dealers.index'));
        $response->assertOk();

        $response->assertSee('id="dealer-filter-form"', false);
        $response->assertSee('data-cascading-filter-form', false);
        $response->assertSee('filter-icon-box', false);
        $response->assertSee('Search Dealer');
        $response->assertSee('Search Area');
        $response->assertSee('Search Brand');
        $response->assertSee('All Areas');
        $response->assertSee('All Brands');
        $response->assertSee('data-cascading="combined-area"', false);
        $response->assertSee('id="dealer-filter-city"', false);
        $response->assertSee('id="dealer-filter-area"', false);
        $response->assertSee('id="dealer-filter-brand"', false);
    }

    public function test_dealer_directory_combined_area_filtering(): void
    {
        $manager = User::factory()->create(['role' => 'pm_manager']);

        // Filter by combined 'Pasig - Metro Manila'
        $response = $this->actingAs($manager)->get(route('dealers.index', [
            'area' => 'Pasig - Metro Manila',
        ]));
        $response->assertOk();

        $dealers = $response->viewData('dealers');
        $this->assertNotEmpty($dealers);
        foreach ($dealers as $dealer) {
            $this->assertEquals('Pasig', $dealer->city);
            $this->assertEquals('Metro Manila', $dealer->area);
        }

        // Available brands should be limited to brands in Pasig
        $brands = $response->viewData('brands');
        $this->assertContains('Mitsubishi', $brands);
        $this->assertContains('Honda', $brands);
        $this->assertNotContains('Changan', $brands);

        // Filter by broad 'Metro Manila'
        $responseArea = $this->actingAs($manager)->get(route('dealers.index', [
            'area' => 'Metro Manila',
        ]));
        $responseArea->assertOk();

        $dealersArea = $responseArea->viewData('dealers');
        $this->assertNotEmpty($dealersArea);
        foreach ($dealersArea as $dealer) {
            $this->assertEquals('Metro Manila', $dealer->area);
        }
    }
}
