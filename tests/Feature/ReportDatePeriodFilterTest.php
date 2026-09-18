<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportDatePeriodFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $manager;

    private User $submitter;

    private Dealer $dealer;

    protected function setUp(): void
    {
        parent::setUp();

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

    public function test_reports_page_has_day_week_and_month_picker_controls(): void
    {
        $this->actingAs($this->manager)->get(route('reports.index'))
            ->assertOk()
            ->assertSee('data-date-period-selector', false)
            ->assertSee('name="period_day"', false)
            ->assertSee('name="period_week"', false)
            ->assertSee('type="week"', false)
            ->assertSee('name="period_month"', false)
            ->assertSee('type="month"', false)
            ->assertSeeInOrder(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
    }

    public function test_day_filter_returns_only_the_selected_weekday(): void
    {
        $firstMonday = $this->requestOn('2026-09-07', 'GPM-DAY-MON-1');
        $secondMonday = $this->requestOn('2026-09-14', 'GPM-DAY-MON-2');
        $tuesday = $this->requestOn('2026-09-15', 'GPM-DAY-TUE');

        $response = $this->actingAs($this->manager)->get(route('reports.index', [
            'date_period' => 'day',
            'period_day' => 'monday',
        ]));

        $response->assertOk()->assertSee('Every Monday');
        $this->assertSameCanonicalIds([$firstMonday->id, $secondMonday->id], $response->viewData('requests')->items());
        $response->assertDontSee($tuesday->reference_no);
    }

    public function test_week_filter_is_limited_to_monday_through_sunday_of_one_iso_week(): void
    {
        $sundayBefore = $this->requestOn('2026-09-13', 'GPM-WEEK-BEFORE');
        $monday = $this->requestOn('2026-09-14', 'GPM-WEEK-MONDAY');
        $sunday = $this->requestOn('2026-09-20', 'GPM-WEEK-SUNDAY');
        $mondayAfter = $this->requestOn('2026-09-21', 'GPM-WEEK-AFTER');

        $response = $this->actingAs($this->manager)->get(route('reports.index', [
            'date_period' => 'week',
            'period_week' => '2026-W38',
        ]));

        $response->assertOk()->assertSee('Sep 14 - Sep 20, 2026');
        $this->assertSameCanonicalIds([$monday->id, $sunday->id], $response->viewData('requests')->items());
        $response->assertDontSee($sundayBefore->reference_no)->assertDontSee($mondayAfter->reference_no);
    }

    public function test_month_filter_is_limited_to_the_selected_calendar_month(): void
    {
        $august = $this->requestOn('2026-08-31', 'GPM-MONTH-BEFORE');
        $first = $this->requestOn('2026-09-01', 'GPM-MONTH-FIRST');
        $last = $this->requestOn('2026-09-30', 'GPM-MONTH-LAST');
        $october = $this->requestOn('2026-10-01', 'GPM-MONTH-AFTER');

        $response = $this->actingAs($this->manager)->get(route('reports.index', [
            'date_period' => 'month',
            'period_month' => '2026-09',
        ]));

        $response->assertOk()->assertSee('September 2026');
        $this->assertSameCanonicalIds([$first->id, $last->id], $response->viewData('requests')->items());
        $response->assertDontSee($august->reference_no)->assertDontSee($october->reference_no);
    }

    public function test_period_value_is_required_and_invalid_iso_weeks_are_rejected(): void
    {
        $this->actingAs($this->manager)
            ->from(route('reports.index'))
            ->get(route('reports.index', ['date_period' => 'day']))
            ->assertRedirect(route('reports.index'))
            ->assertSessionHasErrors('period_day');

        $this->actingAs($this->manager)
            ->from(route('reports.index'))
            ->get(route('reports.index', ['date_period' => 'week', 'period_week' => '2026-W54']))
            ->assertRedirect(route('reports.index'))
            ->assertSessionHasErrors('period_week');
    }

    private function requestOn(string $date, string $reference): PropertyRequest
    {
        return PropertyRequest::create([
            'reference_no' => $reference,
            'dealer_id' => $this->dealer->id,
            'submitted_by' => $this->submitter->id,
            'submitter_name' => $this->submitter->name,
            'designation' => 'Dealer Representative',
            'branch' => $this->dealer->city,
            'area' => $this->dealer->area,
            'request_type' => 'General Repairs',
            'priority' => 'regular',
            'description' => 'Date period filter test request.',
            'request_date' => $date,
            'due_date' => $date,
            'status' => 'completed',
            'completed_at' => $date.' 12:00:00',
        ]);
    }

    /** @param array<int, PropertyRequest> $requests */
    private function assertSameCanonicalIds(array $expectedIds, array $requests): void
    {
        $actualIds = collect($requests)->pluck('id')->all();
        sort($expectedIds);
        sort($actualIds);

        $this->assertSame($expectedIds, $actualIds);
    }
}
