<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordRecoveryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Exercise the delivery check with a sending transport; Notification::fake()
        // intercepts all reset notifications so these tests never contact SMTP.
        config(['mail.default' => 'smtp', 'mail.mailers.smtp.host' => 'smtp.example.test']);
    }

    public function test_admin_cannot_report_success_or_create_a_token_with_a_non_sending_mailer(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        config(['mail.mailers.preview' => ['transport' => 'log']]);

        foreach (['log', 'array', 'preview', 'failover'] as $mailer) {
            config(['mail.default' => $mailer]);
            $this->actingAs($admin)->from(route('admin.users.edit', $user))
                ->post(route('admin.users.password.email', $user))
                ->assertRedirect(route('admin.users.edit', $user))
                ->assertSessionHasErrors('password_email')
                ->assertSessionMissing('status');
        }

        Notification::assertNothingSent();
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
        $this->assertDatabaseMissing('audit_logs', ['action' => 'password_reset_email_sent']);
    }

    public function test_forgot_password_reports_unavailable_email_without_creating_a_reset_token(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        foreach (['log', 'array', 'failover'] as $mailer) {
            config(['mail.default' => $mailer]);
            $this->from(route('password.request'))->post(route('password.email'), ['email' => $user->email])
                ->assertRedirect(route('password.request'))
                ->assertSessionHasErrors(['email' => 'Email password recovery is currently unavailable. Please contact the IT administrator.'])
                ->assertSessionMissing('status');
        }

        Notification::assertNothingSent();
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_admin_can_send_a_user_a_password_reset_email(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'AdminPassword!']);
        $user = User::factory()->create(['email' => 'active@example.com']);

        $this->actingAs($admin)
            ->post(route('admin.users.password.email', $user))
            ->assertRedirect(route('admin.users.edit', $user))
            ->assertSessionHasNoErrors();

        Notification::assertSentTo($user, ResetPassword::class);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'password_reset_email_sent',
            'subject_id' => $user->id,
        ]);
    }

    public function test_admin_can_manually_set_a_password_only_after_confirming_their_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'AdminPassword!']);
        $user = User::factory()->create(['password' => 'OldUserPassword!', 'must_change_password' => false]);
        DB::table('password_reset_tokens')->insert(['email' => $user->email, 'token' => 'old-token', 'created_at' => now()]);

        $payload = [
            'password' => 'TemporaryPassword2026!',
            'password_confirmation' => 'TemporaryPassword2026!',
            'require_password_change' => '1',
        ];

        $this->actingAs($admin)
            ->patch(route('admin.users.password.update', $user), [...$payload, 'admin_password' => 'WrongPassword!'])
            ->assertSessionHasErrors('admin_password');
        $this->assertTrue(Hash::check('OldUserPassword!', $user->fresh()->password));

        $this->patch(route('admin.users.password.update', $user), [...$payload, 'admin_password' => 'AdminPassword!'])
            ->assertRedirect(route('admin.users.edit', $user))
            ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('TemporaryPassword2026!', $user->fresh()->password));
        $this->assertTrue($user->fresh()->must_change_password);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'password_changed_by_admin', 'subject_id' => $user->id]);
    }

    public function test_admin_cannot_change_their_own_password_through_user_management(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'AdminPassword!']);

        $this->actingAs($admin)->post(route('admin.users.password.email', $admin))->assertSessionHasErrors('user');
        $this->patch(route('admin.users.password.update', $admin), [
            'admin_password' => 'AdminPassword!',
            'password' => 'AnotherPassword2026!',
            'password_confirmation' => 'AnotherPassword2026!',
        ])->assertSessionHasErrors('user');

        Notification::assertNothingSent();
        $this->assertTrue(Hash::check('AdminPassword!', $admin->fresh()->password));
    }

    public function test_forgot_password_emails_an_existing_active_user(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'person@example.com', 'is_active' => true]);

        $this->from(route('password.request'))->post(route('password.email'), ['email' => ' PERSON@EXAMPLE.COM '])
            ->assertRedirect(route('password.request'))
            ->assertSessionHas('status', 'Password reset instructions have been sent to your email address.')
            ->assertSessionHasNoErrors();

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_forgot_password_prompts_unknown_or_inactive_users_to_contact_it(): void
    {
        Notification::fake();
        User::factory()->create(['email' => 'inactive@example.com', 'is_active' => false]);

        foreach (['missing@example.com', 'inactive@example.com'] as $email) {
            $this->from(route('password.request'))->post(route('password.email'), ['email' => $email])
                ->assertRedirect(route('password.request'))
                ->assertSessionHasErrors(['email' => 'No active account is registered with this email address. Please contact the IT administrator.']);
        }

        Notification::assertNothingSent();
    }

    public function test_email_authenticated_reset_clears_first_login_requirement(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
            'password' => 'Gateway@2026',
            'must_change_password' => true,
            'email_verified_at' => null,
        ]);
        $token = Password::createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'RecoveredPassword2026!',
            'password_confirmation' => 'RecoveredPassword2026!',
        ])->assertRedirect(route('login'))->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertTrue(Hash::check('RecoveredPassword2026!', $user->password));
        $this->assertFalse($user->must_change_password);
        $this->assertNotNull($user->email_verified_at);
        $this->assertDatabaseHas('audit_logs', ['action' => 'password_reset', 'subject_id' => $user->id]);
    }
}
