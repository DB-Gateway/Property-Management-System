<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use App\Support\MailDelivery;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return auth()->check() ? redirect()->route('dashboard') : view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'The provided email or password is incorrect.',
            ]);
        }

        if (! $request->user()->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'This account is inactive. Contact the system administrator.',
            ]);
        }

        $request->session()->regenerate();
        AuditLog::record('login', $request->user()->name.' signed in.');

        if ($request->user()->must_change_password) {
            return to_route('password.change.edit');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        AuditLog::record('logout', $request->user()->name.' signed out.');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', '');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);
        $request->validate(['email' => ['required', 'email']]);

        $user = User::query()->where('email', $request->email)->where('is_active', true)->first();
        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'No active account is registered with this email address. Please contact the IT administrator.',
            ]);
        }

        if (! MailDelivery::usesSendingTransport()) {
            throw ValidationException::withMessages([
                'email' => 'Email password recovery is currently unavailable. Please contact the IT administrator.',
            ]);
        }

        try {
            $status = Password::sendResetLink(['email' => $user->email]);
        } catch (\Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'email' => 'The reset email could not be sent. Please contact the IT administrator.',
            ]);
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Password reset instructions have been sent to your email address.')
            : back()->withErrors(['email' => $status === Password::INVALID_USER
                ? 'No active account is registered with this email address. Please contact the IT administrator.'
                : __($status)]);
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8', 'max:72'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'must_change_password' => false,
                    'email_verified_at' => $user->email_verified_at ?? now(),
                    'remember_token' => Str::random(60),
                ])->save();

                AuditLog::record('password_reset', "Reset the password for {$user->name} using email authentication.", $user);
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
