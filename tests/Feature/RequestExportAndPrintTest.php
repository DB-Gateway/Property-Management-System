<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use Database\Seeders\DealerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Shuchkin\SimpleXLSX;
use Tests\TestCase;

class RequestExportAndPrintTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DealerSeeder::class);
    }

    private function createRequest(Dealer $dealer, User $submitter, array $overrides = []): PropertyRequest
    {
        static $sequence = 1;

        return PropertyRequest::create(array_merge([
            'reference_no' => 'GPM-20260916-'.str_pad((string) $sequence++, 4, '0', STR_PAD_LEFT),
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

    public function test_dealer_user_sees_export_and_print_actions_on_requests_table(): void
    {
        $dealer = Dealer::firstOrFail();
        $dealerUser = User::factory()->create([
            'role' => 'dealer',
            'dealer_id' => $dealer->id,
        ]);

        $response = $this->actingAs($dealerUser)->get(route('requests.index'));

        $response->assertOk()
            ->assertSee('My Requests')
            ->assertSee('Export Excel')
            ->assertSee('id="export-requests"', false)
            ->assertSee(route('requests.export'), false)
            ->assertSee('id="print-requests"', false)
            ->assertSee('Print')
            ->assertSee('css/requests-print.css')
            ->assertSee('requests-print-summary')
            ->assertSee($dealer->name);
    }

    public function test_dealer_user_can_export_only_their_own_requests(): void
    {
        $dealer1 = Dealer::firstOrFail();
        $dealer2 = Dealer::skip(1)->firstOrFail();

        $dealerUser1 = User::factory()->create([
            'role' => 'dealer',
            'dealer_id' => $dealer1->id,
            'name' => 'Dealer One User',
        ]);
        $dealerUser2 = User::factory()->create([
            'role' => 'dealer',
            'dealer_id' => $dealer2->id,
            'name' => 'Dealer Two User',
        ]);

        $this->createRequest($dealer1, $dealerUser1, [
            'reference_no' => 'REQ-DEALER-001',
            'request_type' => 'Electrical Works',
            'status' => 'pending',
            'priority' => 'urgent',
        ]);

        $this->createRequest($dealer2, $dealerUser2, [
            'reference_no' => 'REQ-OTHER-002',
            'request_type' => 'Plumbing Works',
            'status' => 'pending',
            'priority' => 'regular',
        ]);

        $response = $this->actingAs($dealerUser1)->get(route('requests.export'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringContainsString('My-Requests-', $response->headers->get('Content-Disposition'));

        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        file_put_contents($tempFile, $response->getContent());

        $xlsx = SimpleXLSX::parse($tempFile);
        $this->assertNotFalse($xlsx, 'Generated workbook is valid XLSX.');

        $rows = $xlsx->rows();
        @unlink($tempFile);

        $this->assertGreaterThanOrEqual(2, count($rows));
        $this->assertEquals([
            'Request No.',
            'Branch',
            'Area',
            'Dealer / Brand',
            'Repair Category',
            'Assigned To',
            'Priority',
            'Status',
            'Submitted Date',
            'Inspection Progress',
            'Work Order Progress',
            'Service Report Progress',
            'Completion Progress',
            'Description',
        ], $rows[0]);

        $contentFlattened = array_map(fn ($r) => implode(' ', $r), $rows);
        $allText = implode("\n", $contentFlattened);

        $this->assertStringContainsString('REQ-DEALER-001', $allText);
        $this->assertStringNotContainsString('REQ-OTHER-002', $allText);
    }

    public function test_dealer_export_respects_filters(): void
    {
        $dealer = Dealer::firstOrFail();
        $dealerUser = User::factory()->create([
            'role' => 'dealer',
            'dealer_id' => $dealer->id,
        ]);

        $this->createRequest($dealer, $dealerUser, [
            'reference_no' => 'REQ-URGENT-001',
            'priority' => 'urgent',
            'status' => 'pending',
        ]);

        $this->createRequest($dealer, $dealerUser, [
            'reference_no' => 'REQ-REGULAR-002',
            'priority' => 'regular',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($dealerUser)->get(route('requests.export', ['priority' => 'urgent']));
        $response->assertOk();

        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        file_put_contents($tempFile, $response->getContent());
        $xlsx = SimpleXLSX::parse($tempFile);
        $rows = $xlsx->rows();
        @unlink($tempFile);

        $allText = implode("\n", array_map(fn ($r) => implode(' ', $r), $rows));
        $this->assertStringContainsString('REQ-URGENT-001', $allText);
        $this->assertStringNotContainsString('REQ-REGULAR-002', $allText);
    }

    public function test_pm_manager_can_export_all_requests(): void
    {
        $dealer = Dealer::firstOrFail();
        $dealerUser = User::factory()->create(['role' => 'dealer', 'dealer_id' => $dealer->id]);
        $manager = User::factory()->create(['role' => 'pm_manager']);

        $this->createRequest($dealer, $dealerUser, [
            'reference_no' => 'REQ-MGR-001',
        ]);

        $response = $this->actingAs($manager)->get(route('requests.export'));
        $response->assertOk();
        $this->assertStringContainsString('Requests-', $response->headers->get('Content-Disposition'));

        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        file_put_contents($tempFile, $response->getContent());
        $xlsx = SimpleXLSX::parse($tempFile);
        $rows = $xlsx->rows();
        @unlink($tempFile);

        $allText = implode("\n", array_map(fn ($r) => implode(' ', $r), $rows));
        $this->assertStringContainsString('REQ-MGR-001', $allText);
    }

    public function test_guest_cannot_access_export(): void
    {
        $response = $this->get(route('requests.export'));
        $response->assertRedirect(route('login'));
    }
}
