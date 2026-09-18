<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->must_change_password && ! $request->routeIs('password.change.*', 'logout')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Change your preset password before continuing.',
                    'redirect' => route('password.change.edit'),
                ], 403);
            }

            return to_route('password.change.edit');
        }

        return $next($request);
    }
}
