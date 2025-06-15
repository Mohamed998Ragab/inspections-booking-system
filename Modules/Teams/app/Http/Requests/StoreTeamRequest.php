<?php

namespace Modules\Teams\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Tenant\App\Services\TenantContextService;

class StoreTeamRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'max_concurrent_bookings' => 'sometimes|integer|min:1',
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