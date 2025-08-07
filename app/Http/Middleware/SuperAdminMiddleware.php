<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // User must be logged in
        if (!Auth::check()) {
            // Redirect to your super-admin login page
            return redirect()->route('admin.login');
        }

        // User must be super admin
        if (Auth::user()->user_type != 1) {
            Auth::logout();
            return redirect()->route('admin.login')->withErrors(['email' => 'You are not authorized as Super Admin.']);
        }

        return $next($request);
    }
}
