<?php

namespace App\Http\Middleware;

use App\Support\FiscalYearContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FiscalYearContextMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $context = FiscalYearContext::current();

        if (
            !$context->is_current
            && in_array(strtoupper($request->method()), ['POST', 'PUT', 'PATCH', 'DELETE'], true)
            && !$request->routeIs('fiscal-year.switch')
        ) {
            abort(403, 'Archived fiscal years are read-only. Switch to the current fiscal year before making changes.');
        }

        return $next($request);
    }
}