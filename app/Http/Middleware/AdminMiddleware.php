<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// Runs before admin routes - blocks anyone who isn't logged in as an admin
class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // not logged in, or logged in but not an admin -> 403 forbidden
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }

        return $next($request); // all good, carry on to the controller
    }
}