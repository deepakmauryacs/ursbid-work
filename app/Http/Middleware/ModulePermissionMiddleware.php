<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ModulePermissionMiddleware
{
    public function handle($request, Closure $next, $moduleSlug, $permission)
    {
        $user = Auth::user();
        if (!$user || !$user->hasModulePermission($moduleSlug, $permission)) {
            abort(403);
        }
        return $next($request);
    }
}
