<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !($user->hasRole('admin') || $user->hasRole('SuperAdmin'))) {
            abort(403, 'You do not have permission to access the admin area.');
        }

        return $next($request);
    }
}
