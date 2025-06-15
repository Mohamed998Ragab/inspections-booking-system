<?php

namespace Modules\User\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Tenant\App\Services\TenantContextService;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $tenantContext = app(TenantContextService::class);
        $currentTenant = $tenantContext->getCurrentTenant();
        
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email,NULL,id,tenant_id,' . $currentTenant
            ],
            'password' => 'required|min:8',
            'role' => 'sometimes|in:admin,manager,inspector,customer',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'sometimes|boolean', // Only validate if present

        ];
    }

    protected function prepareForValidation()
    {
        $tenantContext = app(TenantContextService::class);
        $currentTenant = $tenantContext->getCurrentTenant();
        
        if ($currentTenant) {
            $this->merge(['tenant_id' => $currentTenant]);
        }
    }
}