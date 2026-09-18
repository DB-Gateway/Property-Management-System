<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FirstLoginPasswordController extends Controller
{
    public function edit(Request $request)
    {
        return $request->user()->must_change_password
            ? view('auth.change-password')
            : to_route('dashboard');
    }

    public function update(Request $request)
    {
        $user = $request->user();
        abort_unless($user->must_change_password, 403);

        $data = $request->validate([
            'password' => [
                'required', 'string', 'confirmed', 'min:8', 'max:72', Rule::notIn([User::DEFAULT_PASSWORD]),
                function (string $attribute, mixed $value, \Closure $fail) use ($user): void {
                    if (is_string($value) && Hash::check($value, $user->password)) {
                        $fail('Choose a password different from your preset password.');
                    }
                },
            ],
        ], ['password.not_in' => 'Choose a password different from your preset password.']);

        DB::transaction(function () use ($user, $data, $request): void {
            $user->forceFill([
                'password' => $data['password'],
                'must_change_password' => false,
                'remember_token' => Str::random(60),
            ])->save();
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            if (config('session.driver') === 'database') {
                DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                    ->where('user_id', $user->id)->where('id', '!=', $request->session()->getId())->delete();
            }
            AuditLog::record('password_changed', $user->name.' changed their first-login password.', $user);
        });

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))->with('status', 'Your password has been changed successfully.');
    }
}
