<?php

namespace MultiTenantLaravel\App\Http\Middleware;

use Closure;

class MultiTenantMiddleware
{
    public function handle($request, Closure $next)
    {
        // Here we can perform an multi tenant required authentication
        if (!\Auth::check()) {
            return redirect('/login');
        }

        // Assign the user to a tenant if we have one
        if (\Auth::user()->owns()->count() == 1) {
            session()->put('tenant', [
                'id' => \Auth::user()->owns()->first()->id
            ]);
        }

        return $next($request);
    }
}
