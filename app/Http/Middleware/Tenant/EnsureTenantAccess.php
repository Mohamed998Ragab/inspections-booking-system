<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Tenant\App\Services\TenantContextService;

class EnsureTenantAccess
{
    protected $tenantContext;

    public function __construct(TenantContextService $tenantContext)
    {
        $this->tenantContext = $tenantContext;
    }

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        $currentTenant = $this->tenantContext->getCurrentTenant();

        // Enhanced validation
        if (!$currentTenant) {
            // \Log::warning('Request attempted without tenant context', [
            //     'user_id' => $user->id,
            //     'route' => $request->route()->getName(),
            //     'host' => $request->getHost()
            // ]);
            return response()->json(['error' => 'Tenant context not found'], 403);
        }

        if ($user->tenant_id !== $currentTenant) {
            // \Log::warning('Unauthorized tenant access attempted', [
            //     'user_id' => $user->id,
            //     'user_tenant' => $user->tenant_id,
            //     'requested_tenant' => $currentTenant,
            //     'route' => $request->route()->getName()
            // ]);
            return response()->json(['error' => 'Unauthorized tenant access'], 403);
        }

        // Additional check: Ensure user is active
        if (!$user->is_active) {
            return response()->json(['error' => 'Account is inactive'], 403);
        }

        return $next($request);
    }
}

