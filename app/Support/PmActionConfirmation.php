<?php

namespace App\Support;

use Illuminate\Http\Request;

class PmActionConfirmation
{
    public static function validate(Request $request): void
    {
        $request->session()->forget('pm_action_confirmation');

        $request->validate(['current_password' => ['required', 'string', 'current_password']], [
            'current_password.required' => 'Enter your account password to confirm this action.',
            'current_password.current_password' => 'The password does not match your signed-in account.',
        ]);
    }
}
