<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DealerAccountSeeder;
use Database\Seeders\DealerSeeder;
use Database\Seeders\PMManagerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Shuchkin\SimpleXLSX;
use Tests\TestCase;

class DirectoryAccountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_directory_accounts_match_the_workbook_without_sample_requests(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->assertDatabaseCount('dealers', 70);
        $this->assertSame(70, User::where('role', 'dealer')->count());
        $this->assertDatabaseCount('property_requests', 0);

        foreach (SimpleXLSX::parseFile(base_path('Directory_Luzon Dealers.xlsx'))->rows() as $row) {
            if (!is_numeric($row[0])) {
                continue;
            }
            $dealer = Dealer::where('source_no', $row[0])->firstOrFail();
            foreach (['name', 'address', 'point_person_1', 'contact_1', 'point_person_2', 'contact_2'] as $i => $field) {
                $this->assertSame(trim((string) $row[$i + 1]), (string) $dealer->$field);
            }
            $user = $dealer->users()->sole();
            $this->assertMatchesRegularExpression('/^dealer\.[a-z0-9-]+@gateway\.ph$/', $user->email);
            $this->assertTrue(Hash::check(User::DEFAULT_PASSWORD, $user->password));
        }
    }

    public function test_legacy_logins_are_renamed_in_place_and_repeated_seeding_does_not_duplicate_accounts(): void
    {
        $this->seed(DealerSeeder::class);
        $dealer = Dealer::where('source_no', 1)->firstOrFail();
        $legacy = User::factory()->create(['dealer_id' => $dealer->id, 'role' => 'dealer', 'email' => 'dealer@gateway.com']);
        $manager = User::factory()->create(['role' => 'pm_manager', 'email' => 'pmmanager@gateway.com']);
        $other = User::factory()->create(['role' => 'pm_manager', 'email' => 'personal@example.com']);
        $original = $other->fresh()->getAttributes();

        $this->seed([DealerAccountSeeder::class, PMManagerSeeder::class]);
        $this->seed([DealerAccountSeeder::class, PMManagerSeeder::class]);

        $this->assertDatabaseCount('users', 72);
        $this->assertSame('dealer.mitsubishi-pasig@gateway.ph', $legacy->fresh()->email);
        $this->assertSame('pm.manager@gateway.ph', $manager->fresh()->email);
        $this->assertTrue(Hash::check(User::DEFAULT_PASSWORD, $manager->fresh()->password));
        $this->assertSame($original, $other->fresh()->getAttributes());
        $this->assertDatabaseMissing('users', ['email' => 'dealer@gateway.com']);
        $this->assertDatabaseMissing('users', ['email' => 'pmmanager@gateway.com']);
        $this->post(route('login.attempt'), ['email' => $legacy->fresh()->email, 'password' => User::DEFAULT_PASSWORD])->assertRedirect();
        $this->assertAuthenticatedAs($legacy);
    }
}
