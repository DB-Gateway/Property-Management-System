<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use App\Models\WebPushSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_edit_accounts_with_one_role_designation(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $dealer = $this->dealer();

        $this->get(route('admin.users'))->assertOk()
            ->assertSee('Role / Designation')->assertSee('Create User')
            ->assertDontSee('<th>Status</th>', false)->assertDontSee('<th>Designation</th>', false);
        $this->get(route('admin.users.create'))->assertOk()->assertSee('Gateway@2026');

        foreach (User::ROLES as $role => $label) {
            $data = ['name' => 'New '.$label, 'email' => $role.'@example.com', 'role' => $role, 'dealer_id' => $dealer->id];
            $this->post(route('admin.users.store'), [...$data, 'password' => 'IgnoredPassword!', 'must_change_password' => false])
                ->assertRedirect(route('admin.users'))->assertSessionHasNoErrors();

            $user = User::where('email', $data['email'])->firstOrFail();
            $this->assertTrue(Hash::check('Gateway@2026', $user->password));
            $this->assertTrue($user->must_change_password);
            $this->assertTrue($user->is_active);
            $this->assertSame($label, $user->designation);
            $this->assertSame($role === 'dealer' ? $dealer->id : null, $user->dealer_id);
            $this->assertDatabaseHas('audit_logs', ['action' => 'user_created', 'subject_id' => $user->id]);

            $hash = $user->password;
            $this->get(route('admin.users.edit', $user))->assertOk()->assertSee($data['email']);
            $this->put(route('admin.users.update', $user), [
                ...$data, 'name' => 'Edited '.$label, 'role' => 'pm_manager', 'dealer_id' => '', 'password' => 'IgnoredAgain!',
            ])->assertRedirect(route('admin.users'))->assertSessionHasNoErrors();
            $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Edited '.$label, 'role' => 'pm_manager', 'designation' => 'PM Manager', 'dealer_id' => null]);
            $this->assertSame($hash, $user->fresh()->password);
            $this->assertTrue($user->fresh()->must_change_password);
        }
    }

    public function test_dealer_fields_and_information_edit_option_are_available_on_user_forms(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $dealer = $this->dealer();
        $dealer->update([
            'address' => '123 Test Avenue',
            'point_person_1' => 'Juan Dela Cruz',
            'contact_1' => '0917 123 4567',
        ]);
        $dealerUser = User::factory()->create(['role' => 'dealer', 'dealer_id' => $dealer->id]);

        $this->actingAs($admin)->get(route('admin.users.create'))
            ->assertOk()
            ->assertSee('Dealer / Branch')
            ->assertSee('This determines which branch and requests the user can access.')
            ->assertSee($dealer->name)
            ->assertDontSee('Edit Dealer Information');

        $this->get(route('admin.users.edit', $dealerUser))
            ->assertOk()
            ->assertSee('Edit Dealer Information')
            ->assertSee(route('dealers.edit', $dealer), false)
            ->assertSee('123 Test Avenue')
            ->assertSee('Juan Dela Cruz');
    }

    public function test_guests_and_non_admins_cannot_manage_users(): void
    {
        $target = User::factory()->create();
        $routes = [
            ['GET', route('admin.users')], ['GET', route('admin.users.create')],
            ['POST', route('admin.users.store')], ['GET', route('admin.users.edit', $target)],
            ['PUT', route('admin.users.update', $target)], ['DELETE', route('admin.users.destroy', $target)],
        ];
        foreach ($routes as [$method, $url]) {
            $this->call($method, $url)->assertRedirect(route('login'));
        }
        foreach (['dealer', 'pm_manager', 'dial_a', 'pm_support', 'dial_lead'] as $role) {
            $this->actingAs(User::factory()->create(['role' => $role]));
            foreach ($routes as [$method, $url]) {
                $this->call($method, $url)->assertForbidden();
            }
        }
        $this->assertNotSoftDeleted($target);
    }

    public function test_validation_rejects_duplicate_email_invalid_role_and_missing_or_invalid_branch(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        $data = ['name' => 'New User', 'email' => 'new@example.com', 'role' => 'dealer'];
        $this->post(route('admin.users.store'), [...$data, 'name' => '', 'email' => $admin->email, 'role' => 'invalid'])
            ->assertSessionHasErrors(['name', 'email', 'role']);
        $this->post(route('admin.users.store'), [...$data, 'email' => strtoupper($admin->email), 'role' => 'pm_manager'])
            ->assertSessionHasErrors('email');
        $this->post(route('admin.users.store'), $data)->assertSessionHasErrors('dealer_id');
        $this->post(route('admin.users.store'), [...$data, 'dealer_id' => 999999])->assertSessionHasErrors('dealer_id');
        $this->post(route('admin.users.store'), [...$data, 'role' => 'pm_manager'])->assertSessionHasNoErrors();
        $target = User::where('email', $data['email'])->firstOrFail();
        $this->put(route('admin.users.update', $target), [...$data, 'role' => 'admin', 'email' => $admin->email])
            ->assertSessionHasErrors('email');
        $this->assertDatabaseCount('users', 2);
    }

    public function test_admin_cannot_delete_or_demote_their_own_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertSessionHasErrors('user');
        $this->put(route('admin.users.update', $admin), [
            'name' => $admin->name, 'email' => $admin->email, 'role' => 'pm_manager',
        ])->assertSessionHasErrors('role');
        $this->assertNotSoftDeleted($admin);
        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_deletion_blocks_login_removes_devices_and_preserves_request_and_audit_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['password' => 'UserPassword!', 'dealer_id' => $this->dealer()->id]);
        $request = PropertyRequest::withoutEvents(fn () => PropertyRequest::create([
            'reference_no' => 'GPM-USER-DELETE', 'dealer_id' => $user->dealer_id,
            'submitted_by' => $user->id, 'assigned_support_id' => $user->id, 'assigned_manager_id' => $user->id,
            'submitter_name' => $user->name, 'designation' => 'Dealer', 'branch' => 'Pasig', 'area' => 'Metro Manila',
            'request_type' => 'General Repairs', 'priority' => 'regular', 'description' => 'Preserve this request.',
            'request_date' => today(), 'due_date' => today()->addDays(3), 'status' => 'completed', 'completed_at' => now(),
        ]));
        $log = AuditLog::create(['user_id' => $user->id, 'action' => 'login', 'description' => 'Historical sign in.']);
        WebPushSubscription::create([
            'user_id' => $user->id, 'endpoint_hash' => hash('sha256', 'test-device'), 'endpoint' => 'https://example.com/push',
            'public_key' => 'test', 'auth_token' => 'test', 'device_token_hash' => hash('sha256', 'device'),
        ]);
        DB::table('password_reset_tokens')->insert(['email' => $user->email, 'token' => 'test', 'created_at' => now()]);

        $this->actingAs($admin)->delete(route('admin.users.destroy', $user))->assertRedirect(route('admin.users'));
        $this->assertSoftDeleted($user);
        $this->assertNull(User::find($user->id));
        $this->assertDatabaseHas('property_requests', ['id' => $request->id, 'submitted_by' => $user->id]);
        $this->assertSame($user->name, $request->fresh()->submitter->name);
        $this->assertSame($user->name, $request->fresh()->assignedSupport->name);
        $this->assertSame($user->name, $request->fresh()->assignedManager->name);
        $this->assertSame($user->name, $log->fresh()->user->name);
        $this->assertDatabaseMissing('web_push_subscriptions', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
        $this->get(route('admin.users', ['search' => $user->email]))->assertOk()->assertSee('No users match your search.');
        $this->get(route('admin.users.edit', $user))->assertNotFound();
        $this->post(route('logout'));
        $this->post(route('login.attempt'), ['email' => $user->email, 'password' => 'UserPassword!'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_deleted_database_sessions_are_removed_and_existing_guard_sessions_cannot_resume(): void
    {
        config(['session.driver' => 'database']);
        $user = User::factory()->create();
        DB::table('sessions')->insert(['id' => Str::random(40), 'user_id' => $user->id, 'payload' => '', 'last_activity' => time()]);
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->delete(route('admin.users.destroy', $user))->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('sessions', ['user_id' => $user->id]);
        $this->post(route('logout'));
        Auth::forgetGuards();
        $this->withSession([Auth::guard()->getName() => $user->id])->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_deleting_or_changing_the_role_of_dial_a_does_not_block_open_requests(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $dealer = $this->dealer();
        foreach (['delete', 'change_role'] as $operation) {
            $support = User::factory()->create(['role' => 'dial_a']);
            $propertyRequest = PropertyRequest::withoutEvents(fn () => PropertyRequest::create([
                'reference_no' => 'GPM-RELEASE-'.$operation, 'dealer_id' => $dealer->id,
                'submitted_by' => $admin->id, 'assigned_support_id' => $support->id,
                'submitter_name' => $admin->name, 'designation' => 'Dealer', 'branch' => 'Pasig', 'area' => 'Metro Manila',
                'request_type' => 'General Repairs', 'priority' => 'regular', 'description' => 'Continue this request.',
                'request_date' => today(), 'due_date' => today()->addDays(3), 'status' => 'pending',
            ]));
            $this->actingAs($admin);
            if ($operation === 'delete') {
                $this->delete(route('admin.users.destroy', $support))->assertSessionHasNoErrors();
            } else {
                $this->put(route('admin.users.update', $support), [
                    'name' => $support->name, 'email' => $support->email, 'role' => 'pm_manager',
                ])->assertSessionHasNoErrors();
            }
            $this->assertNull($propertyRequest->fresh()->assigned_support_id);
            $replacement = User::factory()->create(['role' => 'dial_a']);
            $this->actingAs($replacement)->patch(route('requests.assign', $propertyRequest))->assertRedirect()->assertSessionHasNoErrors();
            $this->assertSame($replacement->id, $propertyRequest->fresh()->assigned_support_id);
        }
    }

    public function test_dial_a_filter_includes_legacy_role_names(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        foreach (['dial_a', 'pm_support', 'dial_lead'] as $role) {
            User::factory()->create(['role' => $role, 'name' => 'Filter '.$role]);
        }
        $this->get(route('admin.users', ['role' => 'dial_a']))->assertOk()
            ->assertSee('Filter dial_a')->assertSee('Filter pm_support')->assertSee('Filter dial_lead');
    }

    private function dealer(): Dealer
    {
        return Dealer::create(['source_no' => 1, 'name' => 'Test Dealer', 'city' => 'Pasig', 'area' => 'Metro Manila', 'brand' => 'Test']);
    }
}
