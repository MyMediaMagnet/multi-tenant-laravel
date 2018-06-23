<?php

namespace MultiTenantLaravel\App\Http\Middleware;

use Closure;

class MultiTenantMiddleware
{
    public function handle($request, Closure $next)
    {
        // Here we can perform an multi tenant required authentication

        return $next($request);
    }
}
