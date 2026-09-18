<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\User;
use App\Support\DealerData;
use App\Support\SimpleXlsxWriter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Shuchkin\SimpleXLSX;
use Tests\TestCase;

class DealerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_pm_manager_can_open_forms_create_and_edit_a_dealer(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pm_manager']));
        $this->get(route('dealers.create'))->assertOk()->assertSee('Create Dealer');
        $this->get(route('dealers.import'))->assertOk()->assertSee('Download Excel Template');

        $data = $this->data();
        $this->post(route('dealers.store'), $data)->assertRedirect(route('dealers.index'))->assertSessionHasNoErrors();
        $dealer = Dealer::sole();
        $this->assertDatabaseHas('dealers', $data);
        $this->get(route('dealers.edit', $dealer))->assertOk()->assertSee('09171234567');
        $this->get(route('dealers.index'))->assertOk()->assertSee(route('dealers.edit', $dealer), false);

        $data['name'] = 'Updated Dealer';
        $data['contact_2'] = null;
        $this->put(route('dealers.update', $dealer), $data)->assertRedirect(route('dealers.index'))->assertSessionHasNoErrors();
        $this->assertDatabaseHas('dealers', ['id' => $dealer->id, ...$data]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'dealer_created', 'subject_id' => $dealer->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'dealer_updated', 'subject_id' => $dealer->id]);
    }

    public function test_admin_cannot_add_or_import_dealers(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get(route('dealers.index'))
            ->assertOk()
            ->assertDontSee('Add Dealer')
            ->assertDontSee('Import Excel');
        $this->get(route('dealers.create'))->assertForbidden();
        $this->post(route('dealers.store'), $this->data())->assertForbidden();
        $this->get(route('dealers.import'))->assertForbidden();
        $this->post(route('dealers.import.store'))->assertForbidden();
        $this->get(route('dealers.template'))->assertForbidden();
    }

    public function test_dial_users_cannot_add_import_or_edit_dealers_but_can_print_the_directory(): void
    {
        $dealer = Dealer::create($this->data());

        foreach (['dial_a', 'pm_support', 'dial_lead'] as $role) {
            $this->actingAs(User::factory()->create(['role' => $role]));

            $this->get(route('dealers.index'))
                ->assertOk()
                ->assertSee('Print')
                ->assertSee(route('dealers.print'), false)
                ->assertDontSee('Add Dealer')
                ->assertDontSee('Import Excel')
                ->assertDontSee(route('dealers.edit', $dealer), false);

            $this->get(route('dealers.print'))
                ->assertOk()
                ->assertSee('Dealer Directory')
                ->assertSee($dealer->name);

            $this->get(route('dealers.create'))->assertForbidden();
            $this->post(route('dealers.store'), $this->data(['source_no' => 2]))->assertForbidden();
            $this->get(route('dealers.import'))->assertForbidden();
            $this->post(route('dealers.import.store'))->assertForbidden();
            $this->get(route('dealers.edit', $dealer))->assertForbidden();
        }

        $this->assertDatabaseCount('dealers', 1);
    }

    public function test_dealer_validation_and_unique_numbers(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pm_manager']));
        $dealer = Dealer::create($this->data());
        $other = Dealer::create($this->data(['source_no' => 2]));
        $this->post(route('dealers.store'), [
            ...$this->data(), 'name' => '', 'area' => '', 'brand' => '', 'city' => str_repeat('x', 101), 'contact_1' => str_repeat('1', 51),
        ])->assertSessionHasErrors(['source_no', 'name', 'area', 'brand', 'city', 'contact_1']);
        $this->put(route('dealers.update', $other), $this->data())->assertSessionHasErrors('source_no');
        $this->put(route('dealers.update', $dealer), $this->data())->assertSessionHasNoErrors();
        $this->assertDatabaseCount('dealers', 2);
    }

    public function test_guest_and_dealer_accounts_cannot_access_any_dealer_management_endpoint(): void
    {
        $dealer = Dealer::create($this->data());
        $routes = [
            ['GET', route('dealers.create')], ['POST', route('dealers.store')],
            ['GET', route('dealers.edit', $dealer)], ['PUT', route('dealers.update', $dealer)],
            ['GET', route('dealers.import')], ['POST', route('dealers.import.store')],
            ['GET', route('dealers.export')], ['GET', route('dealers.template')],
        ];
        foreach ($routes as [$method, $url]) {
            $this->call($method, $url)->assertRedirect(route('login'));
        }
        $this->actingAs(User::factory()->create(['role' => 'dealer', 'dealer_id' => $dealer->id]));
        foreach ($routes as [$method, $url]) {
            $this->call($method, $url)->assertForbidden();
        }
        $this->assertDatabaseCount('dealers', 1);
    }

    public function test_import_creates_dealers_preserves_contacts_and_skips_existing_numbers_by_default(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pm_manager']));
        $existing = Dealer::create($this->data());
        $rows = [$this->data(['name' => 'Replacement']), $this->data(['source_no' => 2, 'name' => 'New Dealer'])];
        $this->post(route('dealers.import.store'), ['file' => $this->upload($rows)])
            ->assertRedirect(route('dealers.index'))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', 'Import complete: 1 created, 0 updated, 1 skipped.');
        $this->assertSame('Test Dealer', $existing->fresh()->name);
        $this->assertDatabaseHas('dealers', ['source_no' => 2, 'contact_1' => '09171234567', 'contact_2' => '+63 917 765 4321']);
        $this->assertDatabaseHas('audit_logs', ['action' => 'dealers_imported']);
    }

    public function test_import_updates_matching_dealer_in_place_only_when_selected(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pm_manager']));
        $dealer = Dealer::create($this->data());
        $user = User::factory()->create(['role' => 'dealer', 'dealer_id' => $dealer->id]);
        $this->post(route('dealers.import.store'), [
            'file' => $this->upload([$this->data(['name' => 'Changed', 'contact_2' => null])]),
            'update_existing' => '1',
        ])->assertSessionHasNoErrors()->assertSessionHas('status', 'Import complete: 0 created, 1 updated, 0 skipped.');
        $this->assertDatabaseCount('dealers', 1);
        $this->assertSame($dealer->id, $user->fresh()->dealer_id);
        $this->assertDatabaseHas('dealers', ['id' => $dealer->id, 'name' => 'Changed', 'contact_2' => null]);
    }

    public function test_invalid_import_row_prevents_all_creates_and_updates(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pm_manager']));
        $dealer = Dealer::create($this->data());
        $this->post(route('dealers.import.store'), [
            'file' => $this->upload([
                $this->data(['name' => 'Changed']),
                $this->data(['source_no' => 2]),
                $this->data(['source_no' => 3, 'brand' => '']),
            ]),
            'update_existing' => '1',
        ])->assertSessionHasErrors('file')->assertSessionHas('errors', fn ($errors) => str_contains(implode(' ', $errors->get('file')), 'Row 4:'));
        $this->assertSame('Test Dealer', $dealer->fresh()->name);
        $this->assertDatabaseCount('dealers', 1);
        $this->assertDatabaseMissing('audit_logs', ['action' => 'dealers_imported']);
    }

    public function test_import_rejects_duplicate_numbers_missing_headers_empty_and_invalid_files(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pm_manager']));
        $files = [
            $this->upload([$this->data(), $this->data()]),
            UploadedFile::fake()->createWithContent('missing.xlsx', SimpleXlsxWriter::table(['Dealer Name'], [['Example']])),
            $this->upload([]),
            UploadedFile::fake()->createWithContent('broken.xlsx', 'not an Excel workbook'),
            UploadedFile::fake()->createWithContent('dealers.csv', '1,Example'),
        ];
        foreach ($files as $file) {
            $this->post(route('dealers.import.store'), ['file' => $file])->assertSessionHasErrors('file');
        }
        $this->post(route('dealers.import.store'))->assertSessionHasErrors('file');
        $this->assertDatabaseCount('dealers', 0);
    }

    public function test_export_includes_all_filtered_pages_and_preserves_text_as_literal_values(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pm_manager']));
        for ($number = 1; $number <= 17; $number++) {
            Dealer::create($this->data(['source_no' => $number, 'name' => '=SUM(1,2)']));
        }
        Dealer::create($this->data(['source_no' => 18, 'city' => 'Cebu']));
        Dealer::create($this->data(['source_no' => 19, 'brand' => 'Honda']));
        Dealer::create($this->data(['source_no' => 20, 'name' => 'Excluded']));

        $response = $this->get(route('dealers.export', ['city' => 'Pasig', 'area' => 'Metro Manila', 'brand' => 'Mitsubishi', 'search' => '=SUM', 'page' => 2]))
            ->assertOk()->assertDownload()
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $workbook = SimpleXLSX::parseData($response->getContent());
        $this->assertNotFalse($workbook);
        $this->assertSame('Dealers', $workbook->sheetName(0));
        $rows = $workbook->rows();
        $this->assertCount(18, $rows);
        $this->assertSame(array_values(DealerData::HEADERS), $rows[0]);
        $this->assertSame('09171234567', $rows[1][7]);
        $this->assertSame('+63 917 765 4321', $rows[1][9]);
        $this->assertSame('=SUM(1,2)', $rows[1][1]);
        $this->assertSame('', $workbook->rowsEx()[1][1]['f']);

        $this->post(route('dealers.import.store'), [
            'file' => UploadedFile::fake()->createWithContent('export.xlsx', $response->getContent()),
            'update_existing' => '1',
        ])->assertSessionHasNoErrors()->assertSessionHas('status', 'Import complete: 0 created, 17 updated, 0 skipped.');
    }

    public function test_empty_export_and_template_are_valid_workbooks(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pm_manager']));
        foreach (['dealers.export', 'dealers.template'] as $route) {
            $response = $this->get(route($route))->assertOk()->assertDownload();
            $workbook = SimpleXLSX::parseData($response->getContent());
            $this->assertNotFalse($workbook);
            $this->assertSame([array_values(DealerData::HEADERS)], $workbook->rows());
        }
    }

    public function test_import_rejects_oversized_files_and_excessive_rows(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pm_manager']));
        $this->post(route('dealers.import.store'), [
            'file' => $this->upload([$this->data()])->size(5121),
        ])->assertSessionHasErrors('file');

        $rows = array_fill(0, 5001, array_values($this->data()));
        $file = UploadedFile::fake()->createWithContent('too-many.xlsx', SimpleXlsxWriter::table(array_values(DealerData::HEADERS), $rows));
        $this->post(route('dealers.import.store'), ['file' => $file])->assertSessionHasErrors('file')
            ->assertSessionHas('errors', fn ($errors) => str_contains(implode(' ', $errors->get('file')), '5,000'));
        $this->assertDatabaseCount('dealers', 0);
    }

    public function test_invalid_xlsx_archive_is_reported_as_a_validation_error(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pm_manager']));
        $file = $this->upload([$this->data()]);
        // Keep the ZIP signature and content-type metadata, but remove the archive directory.
        $contents = file_get_contents($file->getRealPath());
        file_put_contents($file->getRealPath(), substr($contents, 0, -100));
        $this->post(route('dealers.import.store'), ['file' => $file])->assertSessionHasErrors('file');
        $this->assertDatabaseCount('dealers', 0);
    }

    public function test_import_accepts_reordered_headers_and_blank_rows(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pm_manager']));
        $headers = array_reverse(DealerData::HEADERS);
        $data = $this->data();
        $row = array_map(fn ($field) => $data[$field], array_keys($headers));
        $file = UploadedFile::fake()->createWithContent('reordered.xlsx', SimpleXlsxWriter::table(array_values($headers), [array_fill(0, 10, ''), $row]));
        $this->post(route('dealers.import.store'), ['file' => $file])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('dealers', $data);
    }

    private function upload(array $records): UploadedFile
    {
        $rows = array_map(fn ($record) => array_map(fn ($field) => $record[$field] ?? null, array_keys(DealerData::HEADERS)), $records);

        return UploadedFile::fake()->createWithContent('dealers.xlsx', SimpleXlsxWriter::table(array_values(DealerData::HEADERS), $rows));
    }

    private function data(array $overrides = []): array
    {
        return array_replace([
            'source_no' => 1, 'name' => 'Test Dealer', 'address' => '123 Sample Street',
            'city' => 'Pasig', 'area' => 'Metro Manila', 'brand' => 'Mitsubishi',
            'point_person_1' => 'Contact One', 'contact_1' => '09171234567',
            'point_person_2' => 'Contact Two', 'contact_2' => '+63 917 765 4321',
        ], $overrides);
    }
}
