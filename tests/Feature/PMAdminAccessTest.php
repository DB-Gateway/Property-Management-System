<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use Database\Seeders\PMAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PMAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_pm_admin_has_manager_access_without_system_administrator_access(): void
    {
        $pmAdmin = User::factory()->create(['role' => 'pm_admin', 'name' => 'PM Admin']);
        $this->assertTrue($pmAdmin->isManager());
        $this->assertFalse($pmAdmin->isAdmin());
        $this->assertSame('PM Admin', $pmAdmin->role_label);

        $this->actingAs($pmAdmin);
        foreach (['dashboard', 'requests.index', 'requests.export', 'dealers.index', 'dealers.create', 'reports.index'] as $route) {
            $this->get(route($route))->assertOk();
        }
        $this->get(route('reports.print'))->assertOk()->assertSee('Prepared by PM Admin, PM Admin');
        $this->getJson(route('notifications.index'))->assertOk();
        foreach (['admin.users', 'admin.roles', 'admin.settings', 'admin.reset-requests.show'] as $route) {
            $this->get(route($route))->assertForbidden();
        }
    }

    public function test_system_administrator_can_create_a_pm_admin_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get(route('admin.users.create'))->assertOk()->assertSee('PM Admin');
        $this->post(route('admin.users.store'), [
            'name' => 'Second PM Admin', 'email' => 'second.pm.admin@example.com', 'role' => 'pm_admin',
        ])->assertSessionHasNoErrors()->assertRedirect(route('admin.users'));
        $this->assertDatabaseHas('users', [
            'email' => 'second.pm.admin@example.com', 'role' => 'pm_admin',
            'designation' => 'PM Admin', 'must_change_password' => true,
        ]);
    }

    public function test_pm_admin_priority_changes_require_their_own_password(): void
    {
        $pmAdmin = User::factory()->create(['role' => 'pm_admin', 'password' => 'AdminOwnPassword!']);
        User::factory()->create(['role' => 'pm_manager', 'password' => 'ManagerOwnPassword!']);
        $request = $this->makeRequest();
        $this->actingAs($pmAdmin)->patchJson(route('requests.priority', $request), [
            'priority' => 'urgent', 'current_password' => 'ManagerOwnPassword!',
        ])->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->assertSame('regular', $request->fresh()->priority);

        $this->patchJson(route('requests.priority', $request), [
            'priority' => 'urgent', 'remarks' => 'Immediate repair needed for safe operations.', 'current_password' => 'AdminOwnPassword!',
        ])->assertOk();
        $this->assertSame('urgent', $request->fresh()->priority);
    }

    public function test_active_pm_admin_receives_same_stage_notifications_as_pm_manager(): void
    {
        config(['webpush.public_key' => null, 'webpush.private_key' => null]);
        $pmAdmin = User::factory()->create(['role' => 'pm_admin']);
        $manager = User::factory()->create(['role' => 'pm_manager']);
        $inactive = User::factory()->create(['role' => 'pm_admin', 'is_active' => false]);
        $admin = User::factory()->create(['role' => 'admin']);
        $request = $this->makeRequest();
        $request->update(['inspection_completed_at' => now()]);

        foreach ([$pmAdmin, $manager] as $recipient) {
            $this->assertSame('stage_completed', $recipient->notifications()->sole()->data['kind']);
        }
        $this->assertSame(0, $inactive->notifications()->count());
        $this->assertSame(0, $admin->notifications()->count());
    }

    public function test_seeded_account_can_sign_in_and_reseeding_preserves_changed_password(): void
    {
        $this->seed(PMAdminSeeder::class);
        $pmAdmin = User::where('email', 'pm.admin@gateway.ph')->sole();
        $this->assertSame('pm_admin', $pmAdmin->role);
        $this->assertTrue($pmAdmin->is_active);
        $this->post(route('login.attempt'), [
            'email' => $pmAdmin->email, 'password' => User::DEFAULT_PASSWORD,
        ])->assertRedirect(route('password.change.edit'));
        $this->assertAuthenticatedAs($pmAdmin);

        $pmAdmin->update(['password' => 'ChangedPassword2026!', 'must_change_password' => false]);
        $this->seed(PMAdminSeeder::class);
        $this->assertSame(1, User::where('email', 'pm.admin@gateway.ph')->count());
        $this->assertTrue(Hash::check('ChangedPassword2026!', $pmAdmin->fresh()->password));
        $this->assertFalse($pmAdmin->fresh()->must_change_password);
    }

    private function makeRequest(): PropertyRequest
    {
        $branch = Dealer::create(['source_no' => 101, 'name' => 'Test Dealer', 'city' => 'Pasig', 'area' => 'Metro Manila', 'brand' => 'Test']);
        $dealer = User::factory()->create(['role' => 'dealer', 'dealer_id' => $branch->id]);

        return PropertyRequest::create([
            'reference_no' => 'PMADMIN-001', 'dealer_id' => $branch->id, 'submitted_by' => $dealer->id,
            'submitter_name' => $dealer->name, 'designation' => 'Dealer', 'branch' => 'Pasig',
            'area' => 'Metro Manila', 'request_type' => 'Electrical Works', 'priority' => 'regular',
            'description' => 'Repair showroom lighting.', 'request_date' => today(),
            'due_date' => today()->addDays(4), 'status' => 'pending',
        ]);
    }
}
