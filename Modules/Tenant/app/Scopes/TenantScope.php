<?php

namespace Modules\Tenant\App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

use Modules\Tenant\App\Services\TenantContextService;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $tenantContext = app(TenantContextService::class);
        $currentTenant = $tenantContext->getCurrentTenant();

        // Check if current user is a superadmin
        if (Auth::check() && $this->isSuperAdmin(Auth::user())) {
            // Superadmins can see all records, so don't apply tenant scope
            return;
        }

        if ($currentTenant) {
            $builder->where($model->getTable() . '.tenant_id', $currentTenant);
        }
    }

    /**
     * Check if the given user is a superadmin
     */
    private function isSuperAdmin($user): bool
    {
        return $user &&
            $user->role === 'superadmin' &&
            $user->tenant_id === null;
    }
}
