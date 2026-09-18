<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use Database\Seeders\DealerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Shuchkin\SimpleXLSX;
use Tests\TestCase;

class MonthFilterEnforcementTest extends TestCase
{
    use RefreshDatabase;

    private User $manager;

    private User $dealerUser;

    private Dealer $dealer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DealerSeeder::class);

        $this->dealer = Dealer::firstOrFail();
        $this->manager = User::factory()->create(['role' => 'pm_manager']);
        $this->dealerUser = User::factory()->create([
            'role' => 'dealer',
            'dealer_id' => $this->dealer->id,
        ]);
    }

    private function createRequestForDate(string $date, string $reference): PropertyRequest
    {
        return PropertyRequest::create([
            'reference_no' => $reference,
            'dealer_id' => $this->dealer->id,
            'dealer_name' => $this->dealer->brand ?: $this->dealer->name,
            'submitted_by' => $this->dealerUser->id,
            'submitter_name' => $this->dealerUser->name,
            'designation' => 'Dealer Representative',
            'branch' => $this->dealer->city ?: $this->dealer->branch,
            'area' => $this->dealer->area,
            'request_type' => 'Electrical Works',
            'priority' => 'regular',
            'description' => 'Month filter enforcement test request.',
            'request_date' => $date,
            'due_date' => $date,
            'status' => 'completed',
            'completed_at' => $date.' 12:00:00',
        ]);
    }

    public function test_requests_table_defaults_to_current_month_and_excludes_other_months(): void
    {
        $august = $this->createRequestForDate('2026-08-15', 'GPM-REQ-AUG');
        $september = $this->createRequestForDate('2026-09-10', 'GPM-REQ-SEP');
        $october = $this->createRequestForDate('2026-10-05', 'GPM-REQ-OCT');

        $currentMonth = now()->format('Y-m');

        $response = $this->actingAs($this->manager)->get(route('requests.index'));

        $response->assertOk();
        $response->assertSee($september->reference_no);
        $response->assertDontSee($august->reference_no);
        $response->assertDontSee($october->reference_no);
        $response->assertSee('name="month"', false);
        $response->assertSee('value="'.$currentMonth.'"', false);
    }

    public function test_requests_table_is_accessible_when_manually_filtered_on_a_specific_month(): void
    {
        $august = $this->createRequestForDate('2026-08-15', 'GPM-REQ-AUG-2');
        $september = $this->createRequestForDate('2026-09-10', 'GPM-REQ-SEP-2');
        $october = $this->createRequestForDate('2026-10-05', 'GPM-REQ-OCT-2');

        // Filter August
        $responseAug = $this->actingAs($this->manager)->get(route('requests.index', ['month' => '2026-08']));
        $responseAug->assertOk();
        $responseAug->assertSee($august->reference_no);
        $responseAug->assertDontSee($september->reference_no);
        $responseAug->assertDontSee($october->reference_no);
        $responseAug->assertSee('value="2026-08"', false);

        // Filter October
        $responseOct = $this->actingAs($this->manager)->get(route('requests.index', ['month' => '2026-10']));
        $responseOct->assertOk();
        $responseOct->assertSee($october->reference_no);
        $responseOct->assertDontSee($august->reference_no);
        $responseOct->assertDontSee($september->reference_no);
        $responseOct->assertSee('value="2026-10"', false);
    }


    public function test_requests_excel_export_respects_month_filter(): void
    {
        $august = $this->createRequestForDate('2026-08-15', 'GPM-EXP-AUG');
        $september = $this->createRequestForDate('2026-09-10', 'GPM-EXP-SEP');

        // Export with default (current month)
        $responseSep = $this->actingAs($this->manager)->get(route('requests.export'));
        $responseSep->assertOk();

        $tempFileSep = tempnam(sys_get_temp_dir(), 'xlsx');
        file_put_contents($tempFileSep, $responseSep->getContent());
        $xlsxSep = SimpleXLSX::parse($tempFileSep);
        $textSep = implode("\n", array_map(fn ($r) => implode(' ', $r), $xlsxSep->rows()));
        @unlink($tempFileSep);

        $this->assertStringContainsString($september->reference_no, $textSep);
        $this->assertStringNotContainsString($august->reference_no, $textSep);

        // Export August
        $responseAug = $this->actingAs($this->manager)->get(route('requests.export', ['month' => '2026-08']));
        $responseAug->assertOk();

        $tempFileAug = tempnam(sys_get_temp_dir(), 'xlsx');
        file_put_contents($tempFileAug, $responseAug->getContent());
        $xlsxAug = SimpleXLSX::parse($tempFileAug);
        $textAug = implode("\n", array_map(fn ($r) => implode(' ', $r), $xlsxAug->rows()));
        @unlink($tempFileAug);

        $this->assertStringContainsString($august->reference_no, $textAug);
        $this->assertStringNotContainsString($september->reference_no, $textAug);
    }

    public function test_reports_table_defaults_to_current_month_and_excludes_other_months(): void
    {
        $august = $this->createRequestForDate('2026-08-15', 'GPM-REP-AUG');
        $september = $this->createRequestForDate('2026-09-10', 'GPM-REP-SEP');
        $october = $this->createRequestForDate('2026-10-05', 'GPM-REP-OCT');

        $response = $this->actingAs($this->manager)->get(route('reports.index'));

        $response->assertOk();
        $response->assertSee($september->reference_no);
        $response->assertDontSee($august->reference_no);
        $response->assertDontSee($october->reference_no);
        $response->assertSee('September 2026');
        $response->assertSee('value="2026-09"', false);
    }

    public function test_reports_table_is_accessible_when_manually_filtered_on_a_specific_month(): void
    {
        $august = $this->createRequestForDate('2026-08-15', 'GPM-REP-AUG-2');
        $september = $this->createRequestForDate('2026-09-10', 'GPM-REP-SEP-2');

        $response = $this->actingAs($this->manager)->get(route('reports.index', [
            'date_period' => 'month',
            'period_month' => '2026-08',
        ]));

        $response->assertOk();
        $response->assertSee($august->reference_no);
        $response->assertDontSee($september->reference_no);
        $response->assertSee('August 2026');
        $response->assertSee('value="2026-08"', false);
    }

    public function test_reports_weekday_filter_excludes_other_months(): void
    {
        // Both are Mondays, but in different months
        $augustMonday = $this->createRequestForDate('2026-08-10', 'GPM-REP-MON-AUG');
        $septemberMonday = $this->createRequestForDate('2026-09-07', 'GPM-REP-MON-SEP');

        $response = $this->actingAs($this->manager)->get(route('reports.index', [
            'date_period' => 'day',
            'period_day' => 'monday',
        ]));

        $response->assertOk();
        $response->assertSee('Every Monday');
        $response->assertSee($septemberMonday->reference_no);
        $response->assertDontSee($augustMonday->reference_no);
    }
}
