<?php

namespace App\Http\Middleware\Role;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        
        // Check if user is superadmin (tenant_id should be null for superadmins)
        if ($user->tenant_id !== null || $user->role !== 'superadmin') {
            return response()->json([
                'error' => 'Superadmin access required'
            ], 403);
        }

        return $next($request);
    }
}
