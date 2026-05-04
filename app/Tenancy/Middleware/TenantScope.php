<?php

namespace App\Tenancy\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantScope
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return $next($request);
        }

        if ($request->user()->isSystemLevel()) {
            return $next($request);
        }

        if (!$request->user()->tenant_id) {
            return response()->json(['error' => 'User not associated with any tenant'], 403);
        }

        return $next($request);
    }
}
