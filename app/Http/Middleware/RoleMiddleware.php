<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = (string) $request->route('role', 'admin');

        if (! $request->user() || ! $request->user()->hasRole($role)) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
