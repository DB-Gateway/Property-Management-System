<?php

namespace App\Listeners;

use App\Models\WebPushSubscription;
use Illuminate\Auth\Events\Login;

class RemovePreviousAccountPushOnLogin
{
    public function handle(Login $event): void
    {
        $token = request()->cookie(WebPushSubscription::COOKIE);
        if ($event->guard === 'web' && is_string($token) && $token !== '') {
            WebPushSubscription::where('device_token_hash', hash('sha256', $token))
                ->where('user_id', '!=', $event->user->getAuthIdentifier())->delete();
        }
    }
}
