<?php

namespace Modules\Tenant\App\Services;

use Illuminate\Http\Request;
use Modules\Tenant\App\Models\Tenant;

class TenantContextService
{
    private $currentTenantId = null;

    public function setCurrentTenant($tenantId)
    {
        $this->currentTenantId = $tenantId;
    }

    public function getCurrentTenant()
    {
        return $this->currentTenantId;
    }

    public function getCurrentTenantModel()
    {
        if ($this->currentTenantId) {
            return Tenant::find($this->currentTenantId);
        }
        return null;
    }

    public function resolveTenantFromRequest(Request $request)
    {
        $host = $request->getHost();
        $parts = explode('.', $host);
        
        // Try subdomain resolution (works for both localhost and production)
        // Examples: tenantone.localhost, tenantone.inspections-booking.com
        if (count($parts) >= 2) {
            $subdomain = $parts[0];
            
            // Skip 'www' subdomain
            if ($subdomain !== 'www') {
                $tenant = Tenant::where('slug', $subdomain)->where('is_active', true)->first();
                if ($tenant) return $tenant;
            }
        }
        
        // Try full domain match (for custom domains)
        // Example: tenantone.com
        $tenant = Tenant::where('domain', $host)->where('is_active', true)->first();
        if ($tenant) return $tenant;
        
        // Handle www prefix for custom domains
        if (str_starts_with($host, 'www.')) {
            $cleanHost = substr($host, 4);
            $tenant = Tenant::where('domain', $cleanHost)->where('is_active', true)->first();
            if ($tenant) return $tenant;
        }

        // Try from header (for API testing)
        $tenantSlug = $request->header('X-Tenant-Slug');
        if ($tenantSlug) {
            return Tenant::where('slug', $tenantSlug)->where('is_active', true)->first();
        }

        return null;
    }
}