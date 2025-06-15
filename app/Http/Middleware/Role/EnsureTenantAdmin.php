<?php

namespace App\Http\Middleware\Role;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTenantAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        
        // Check if user is admin within their tenant
        if ($user->role !== 'admin') {
            return response()->json([
                'error' => 'Tenant admin access required'
            ], 403);
        }

        // Ensure user belongs to a tenant (not superadmin)
        if ($user->tenant_id === null) {
            return response()->json([
                'error' => 'This operation requires tenant context'
            ], 403);
        }

        return $next($request);
    }
}