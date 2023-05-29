<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and has 'is_admin' flag set to 1
        if ($request->user() && $request->user()->is_admin == 1) {
            return $next($request);
        }

        // User is not an admin, redirect them or show an error message
        return redirect()->route('FrontEnd')->with('error', 'You are not authorized to access this page.');
    }
}
