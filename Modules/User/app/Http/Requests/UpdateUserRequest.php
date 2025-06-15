<?php


namespace Modules\User\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Tenant\App\Services\TenantContextService;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Get the user ID from route parameter
        $userId = $this->route('user') ? $this->route('user') : $this->route('id');
        
        // Get current tenant context for unique email validation
        $tenantContext = app(TenantContextService::class);
        $currentTenant = $tenantContext->getCurrentTenant();
        
        return [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                'unique:users,email,' . $userId . ',id,tenant_id,' . $currentTenant
            ],
            'password' => 'sometimes|min:8',
            'role' => 'sometimes|in:admin,manager,inspector,customer',
            'phone' => 'sometimes|nullable|string|max:20',
            'is_active' => 'sometimes|boolean',
        ];
    }

    // Add this method to see what's being validated
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Log what's being validated (remove this after debugging)
        // \Log::info('UpdateUserRequest validated data:', $validated);
        
        return $validated;
    }
}