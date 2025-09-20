<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized.');
        }

        // Check role with Spatie
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}