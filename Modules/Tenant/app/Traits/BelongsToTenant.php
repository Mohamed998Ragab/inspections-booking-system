<?php

namespace Modules\Tenant\App\Traits;

use Illuminate\Support\Facades\Auth;

use Modules\Tenant\App\Scopes\TenantScope;
use Modules\Tenant\App\Services\TenantContextService;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            $tenantContext = app(TenantContextService::class);
            $currentTenant = $tenantContext->getCurrentTenant();

            // Don't auto-assign tenant_id if user is superadmin or if tenant_id is already set
            if ($model->tenant_id || static::isSuperAdminCreating()) {
                return;
            }


            if ($currentTenant && !$model->tenant_id) {
                $model->tenant_id = $currentTenant;
            }
        });
    }

    /**
     * Check if a superadmin is creating the record
     */
    protected static function isSuperAdminCreating(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        return $user->role === 'superadmin' && $user->tenant_id === null;
    }

    public function tenant()
    {
        return $this->belongsTo(\Modules\Tenant\App\Models\Tenant::class);
    }

    // Method to bypass tenant scope when needed (admin operations)
    public function scopeWithoutTenantScope($query)
    {
        return $query->withoutGlobalScope(TenantScope::class);
    }
}
