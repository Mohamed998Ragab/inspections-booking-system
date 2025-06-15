<?php 

namespace Modules\Auth\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Tenant\App\Services\TenantContextService;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        $tenantId = app(TenantContextService::class)->getCurrentTenant();
    
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where('tenant_id', $tenantId) // Remove ignore()
            ],
            'password' => 'required|min:8',
            'role' => 'sometimes|in:admin,manager,inspector,customer',
            'phone' => 'required|string|max:20',

        ];
    }
}