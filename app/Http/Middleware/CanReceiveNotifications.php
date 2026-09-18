<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CanReceiveNotifications
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()?->is_active && ! $request->user()->isAdmin(), 403);
        abort_if($request->hasHeader('X-PMS-Account') && $request->header('X-PMS-Account') !== (string) $request->user()->id, 409);

        return $next($request)->header('Cache-Control', 'private, no-store');
    }
}
