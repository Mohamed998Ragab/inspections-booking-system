<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Tenant\App\Services\TenantContextService;

class TenantScopeMiddleware
{
    protected $tenantContext;

    public function __construct(TenantContextService $tenantContext)
    {
        $this->tenantContext = $tenantContext;
    }

    public function handle(Request $request, Closure $next)
    {

        // First try to resolve from request (domain/subdomain/header)
        if (!$this->tenantContext->getCurrentTenant()) {
            $tenant = $this->tenantContext->resolveTenantFromRequest($request);
            if ($tenant) {
                $this->tenantContext->setCurrentTenant($tenant->id);
            }
        }


        // Then set from authenticated user if available
        if (Auth::check() && !$this->tenantContext->getCurrentTenant()) {
            $this->tenantContext->setCurrentTenant(Auth::user()->tenant_id);
        }

        

        return $next($request);
    }
}
