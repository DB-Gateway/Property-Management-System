<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FirstLoginPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_login_shows_required_modal_before_the_intended_page(): void
    {
        $user = $this->newUser();
        $this->get(route('admin.users'))->assertRedirect(route('login'));
        $this->post(route('login.attempt'), ['email' => $user->email, 'password' => 'Gateway@2026', 'remember' => 1])
            ->assertRedirect(route('password.change.edit'));
        $this->get(route('password.change.edit'))->assertOk()
            ->assertSee('data-first-login-dialog', false)->assertSee('Confirm New Password')->assertSee('Sign out')
            ->assertDontSee('Gateway@2026')->assertDontSee('browserPushConfig');
        $this->assertTrue($user->fresh()->must_change_password);
    }

    public function test_pending_accounts_cannot_bypass_the_password_change_for_any_role(): void
    {
        foreach (array_keys(User::ROLES) as $role) {
            $this->actingAs($this->newUser(['role' => $role]));
            foreach (['dashboard', 'requests.index', 'requests.create', 'profile.edit', 'admin.users', 'dealers.index', 'reports.print'] as $route) {
                $this->get(route($route))->assertRedirect(route('password.change.edit'));
            }
            $this->post(route('requests.store'), [])->assertRedirect(route('password.change.edit'));
            $this->post(route('admin.users.store'), [])->assertRedirect(route('password.change.edit'));
            $this->patch(route('profile.update'), [])->assertRedirect(route('password.change.edit'));
            $this->getJson(route('notifications.index'))->assertForbidden()->assertJsonPath('redirect', route('password.change.edit'));
        }
        $this->assertDatabaseCount('property_requests', 0);
    }

    public function test_password_must_be_confirmed_long_enough_and_different_from_preset(): void
    {
        $user = $this->newUser();
        $this->actingAs($user);
        $passwords = [
            ['password' => 'short', 'password_confirmation' => 'short'],
            ['password' => 'NewPassword2026!', 'password_confirmation' => 'DifferentPassword!'],
            ['password' => 'Gateway@2026', 'password_confirmation' => 'Gateway@2026'],
            ['password' => str_repeat('x', 73), 'password_confirmation' => str_repeat('x', 73)],
        ];
        foreach ($passwords as $data) {
            $this->from(route('password.change.edit'))->put(route('password.change.update'), $data)
                ->assertRedirect(route('password.change.edit'))->assertSessionHasErrors('password');
            $this->assertTrue($user->fresh()->must_change_password);
            $this->assertTrue(Hash::check('Gateway@2026', $user->fresh()->password));
        }
        $this->get(route('password.change.edit'))->assertOk()->assertSee('role="alert"', false);
    }

    public function test_successful_password_change_unlocks_account_and_is_not_prompted_again(): void
    {
        $user = $this->newUser();
        $token = $user->remember_token;
        $this->post(route('login.attempt'), ['email' => $user->email, 'password' => 'Gateway@2026']);
        $this->withSession(['url.intended' => route('admin.users')])
            ->put(route('password.change.update'), ['password' => 'NewPassword2026!', 'password_confirmation' => 'NewPassword2026!'])
            ->assertRedirect(route('admin.users'))->assertSessionHasNoErrors();
        $this->assertFalse($user->fresh()->must_change_password);
        $this->assertTrue(Hash::check('NewPassword2026!', $user->fresh()->password));
        $this->assertNotSame($token, $user->fresh()->remember_token);
        $this->assertDatabaseHas('audit_logs', ['action' => 'password_changed', 'user_id' => $user->id]);
        $this->get(route('dashboard'))->assertOk();
        $this->get(route('password.change.edit'))->assertRedirect(route('dashboard'));
        $this->post(route('logout'));
        $this->post(route('login.attempt'), ['email' => $user->email, 'password' => 'Gateway@2026'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->post(route('login.attempt'), ['email' => $user->email, 'password' => 'NewPassword2026!'])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_signing_out_before_changing_password_keeps_the_requirement(): void
    {
        $user = $this->newUser();
        $this->actingAs($user)->post(route('logout'))->assertRedirect(route('login'));
        $this->assertTrue($user->fresh()->must_change_password);
        $this->post(route('login.attempt'), ['email' => $user->email, 'password' => 'Gateway@2026'])
            ->assertRedirect(route('password.change.edit'));
    }

    public function test_existing_accounts_keep_their_normal_login_and_cannot_use_first_login_endpoint(): void
    {
        $user = User::factory()->create(['role' => 'admin', 'password' => 'ExistingPassword!']);
        $this->post(route('login.attempt'), ['email' => $user->email, 'password' => 'ExistingPassword!'])->assertRedirect(route('dashboard'));
        $this->get(route('password.change.edit'))->assertRedirect(route('dashboard'));
        $this->put(route('password.change.update'), ['password' => 'BypassPassword!', 'password_confirmation' => 'BypassPassword!'])->assertForbidden();
        $this->assertTrue(Hash::check('ExistingPassword!', $user->fresh()->password));
    }

    public function test_guests_cannot_access_password_change(): void
    {
        $this->get(route('password.change.edit'))->assertRedirect(route('login'));
        $this->put(route('password.change.update'), [])->assertRedirect(route('login'));
    }

    private function newUser(array $attributes = []): User
    {
        return User::factory()->create([...$attributes, 'role' => $attributes['role'] ?? 'admin', 'password' => 'Gateway@2026', 'must_change_password' => true]);
    }
}
