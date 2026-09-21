<?php

namespace App\Support;

use Illuminate\Http\Request;

class PmActionConfirmation
{
    public static function available(Request $request): bool
    {
        $proof = $request->session()->get('pm_action_confirmation');
        $user = $request->user();

        return $user?->isManager() && $user->is_active && is_array($proof)
            && ($proof['user_id'] ?? null) === $user->id
            && ($proof['expires_at'] ?? 0) > now()->timestamp
            && hash_equals($proof['fingerprint'] ?? '', hash('sha256', $user->getAuthPassword().$user->role));
    }

    public static function validate(Request $request): void
    {
        if ($request->boolean('quick_confirm') && ! $request->filled('current_password') && self::available($request)) {
            return;
        }

        $request->validate(['current_password' => ['required', 'string', 'current_password']], [
            'current_password.required' => 'Enter your account password to enable confirmation for this sign-in.',
            'current_password.current_password' => 'The password does not match your signed-in account.',
        ]);

        if ($request->user()->isManager() && $request->boolean('remember_confirmation')) {
            $request->session()->put('pm_action_confirmation', [
                'user_id' => $request->user()->id,
                'fingerprint' => hash('sha256', $request->user()->getAuthPassword().$request->user()->role),
                'expires_at' => now()->addHours(12)->timestamp,
            ]);
        } else {
            $request->session()->forget('pm_action_confirmation');
        }
    }
}
