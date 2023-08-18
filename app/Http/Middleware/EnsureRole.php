<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if ($user && in_array($user->role, $roles)) {
            return $next($request);
        }

        return response('Unauthorized.', 401);
    }
}
