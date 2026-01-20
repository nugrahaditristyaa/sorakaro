<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * Redirect admin users away from user-only areas.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->hasRole('admin')) {
            return redirect('/admin')->with('warning', 'Admins should use the admin panel.');
        }

        return $next($request);
    }
}
