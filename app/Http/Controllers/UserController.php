<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use App\Models\WebPushSubscription;
use App\Support\MailDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $this->adminOnly($request);

        return view('admin.users.form', $this->formData(new User(['role' => 'dealer'])));
    }

    public function store(Request $request)
    {
        $this->adminOnly($request);
        $data = $this->validatedUser($request);

        DB::transaction(function () use ($data): void {
            $user = User::create([
                ...$data,
                'password' => User::DEFAULT_PASSWORD,
                'must_change_password' => true,
                'is_active' => true,
            ]);
            AuditLog::record('user_created', "Created user {$user->name} ({$user->email}).", $user);
        });

        return to_route('admin.users')->with('status', 'User created successfully. They must change the preset password when they first sign in.');
    }

    public function edit(Request $request, User $user)
    {
        $this->adminOnly($request);

        return view('admin.users.form', $this->formData($user));
    }

    public function update(Request $request, User $user)
    {
        $this->adminOnly($request);
        $data = $this->validatedUser($request, $user);

        if ($user->is($request->user()) && $data['role'] !== 'admin') {
            throw ValidationException::withMessages(['role' => 'You cannot remove your own administrator role.']);
        }

        DB::transaction(function () use ($user, $data): void {
            if ($user->email !== $data['email']) {
                DB::table('password_reset_tokens')->where('email', $user->email)->delete();
                $user->email_verified_at = null;
            }
            $user->fill($data)->save();
            if (! $user->isDialA()) {
                $this->releaseOpenAssignments($user);
            }
            AuditLog::record('user_updated', "Updated user {$user->name} ({$user->email}).", $user);
        });

        return to_route('admin.users')->with('status', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        $this->adminOnly($request);

        if ($user->is($request->user())) {
            throw ValidationException::withMessages(['user' => 'You cannot delete your own administrator account.']);
        }

        DB::transaction(function () use ($user): void {
            AuditLog::record('user_deleted', "Deleted user {$user->name} ({$user->email}).", $user);
            $user->forceFill(['is_active' => false, 'remember_token' => Str::random(60)])->save();
            $this->releaseOpenAssignments($user);
            $user->delete();
            WebPushSubscription::where('user_id', $user->id)->delete();
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            if (config('session.driver') === 'database') {
                DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                    ->where('user_id', $user->id)->delete();
            }
        });

        return to_route('admin.users')->with('status', 'User deleted successfully. Their request history has been preserved.');
    }

    public function sendPasswordReset(Request $request, User $user)
    {
        $this->adminOnly($request);
        $this->ensureDifferentUser($request, $user);

        if (! MailDelivery::usesSendingTransport()) {
            throw ValidationException::withMessages([
                'password_email' => 'Outgoing email is not configured for delivery. Ask IT to configure the email service, or use the manual password option.',
            ]);
        }

        try {
            $status = Password::sendResetLink(['email' => $user->email]);
        } catch (\Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'password_email' => 'The reset email could not be sent. Confirm the mail settings or use the manual password option.',
            ]);
        }

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages(['password_email' => __($status)]);
        }

        AuditLog::record('password_reset_email_sent', "Sent a password reset email to {$user->name} ({$user->email}).", $user);

        return to_route('admin.users.edit', $user)->with('status', "Password reset instructions were sent to {$user->email}.");
    }

    public function updatePassword(Request $request, User $user)
    {
        $this->adminOnly($request);
        $this->ensureDifferentUser($request, $user);

        $data = $request->validate([
            'admin_password' => ['required', 'string', 'current_password'],
            'password' => [
                'required', 'string', 'confirmed', 'min:8', 'max:72',
                function (string $attribute, mixed $value, \Closure $fail) use ($user): void {
                    if (is_string($value) && Hash::check($value, $user->password)) {
                        $fail('The new password must be different from the user\'s current password.');
                    }
                },
            ],
            'require_password_change' => ['sometimes', 'boolean'],
        ], [
            'admin_password.current_password' => 'Your administrator password is incorrect.',
        ]);

        $mustChangePassword = $request->boolean('require_password_change');

        DB::transaction(function () use ($user, $data, $mustChangePassword): void {
            $user->forceFill([
                'password' => $data['password'],
                'must_change_password' => $mustChangePassword,
                'remember_token' => Str::random(60),
            ])->save();
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            if (config('session.driver') === 'database') {
                DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                    ->where('user_id', $user->id)->delete();
            }
            AuditLog::record(
                'password_changed_by_admin',
                "Changed the password for {$user->name} ({$user->email}).",
                $user,
                ['must_change_password' => $mustChangePassword]
            );
        });

        $message = $mustChangePassword
            ? 'Temporary password saved. The user must choose a new password at their next sign-in.'
            : 'The user password was updated successfully.';

        return to_route('admin.users.edit', $user)->with('status', $message);
    }

    private function adminOnly(Request $request): void
    {
        abort_unless($request->user()->isAdmin(), 403);
    }

    private function ensureDifferentUser(Request $request, User $user): void
    {
        if ($user->is($request->user())) {
            throw ValidationException::withMessages([
                'user' => 'Use My Profile to change your own administrator password.',
            ]);
        }
    }

    private function releaseOpenAssignments(User $user): void
    {
        PropertyRequest::where('assigned_support_id', $user->id)
            ->whereNull('completed_at')->where('status', '!=', 'completed')
            ->update(['assigned_support_id' => null]);
    }

    private function formData(User $user): array
    {
        return [
            'user' => $user,
            'roles' => User::ROLES,
            'dealers' => Dealer::orderBy('name')->get(),
        ];
    }

    private function validatedUser(Request $request, ?User $user = null): array
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
            'email' => Str::lower(trim((string) $request->input('email'))),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'dealer_id' => ['nullable', 'required_if:role,dealer', 'integer', Rule::exists('dealers', 'id')],
        ], [
            'dealer_id.required_if' => 'Select a dealer / branch for a Dealer account.',
            'email.unique' => 'This email is already assigned to an account.',
        ]);

        return [
            ...$data,
            'dealer_id' => $data['role'] === 'dealer' ? ($data['dealer_id'] ?? null) : null,
            'designation' => User::ROLES[$data['role']],
        ];
    }
}
