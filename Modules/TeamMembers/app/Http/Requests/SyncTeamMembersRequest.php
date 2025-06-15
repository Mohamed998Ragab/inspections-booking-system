<?php

namespace Modules\TeamMembers\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Tenant\App\Services\TenantContextService;
use Modules\User\App\Models\User;
use Illuminate\Validation\Rule;

class SyncTeamMembersRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $tenantContext = app(TenantContextService::class);
        $currentTenantId = $tenantContext->getCurrentTenant();

        return [
            'user_ids' => 'nullable|array', // Allow empty array to remove all members
            'user_ids.*' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) use ($currentTenantId) {
                    return $query->where('tenant_id', $currentTenantId)
                                ->where('is_active', true);
                }),
            ],
        ];
    }

    public function messages()
    {
        return [
            'user_ids.array' => 'User IDs must be provided as an array',
            'user_ids.*.exists' => 'One or more user IDs are invalid or do not belong to your tenant',
            'user_ids.*.integer' => 'User IDs must be integers',
            'user_ids.*.required' => 'User ID is required when provided',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $tenantContext = app(TenantContextService::class);
            $currentTenantId = $tenantContext->getCurrentTenant();
            
            if (!$currentTenantId) {
                $validator->errors()->add('tenant', 'No tenant context available');
                return;
            }

            $userIds = $this->input('user_ids', []);
            
            // Only validate if user_ids is not empty
            if (!empty($userIds)) {
                // Additional validation to ensure all users belong to current tenant
                $validUserCount = User::whereIn('id', $userIds)
                    ->where('tenant_id', $currentTenantId)
                    ->where('is_active', true)
                    ->count();
                
                if ($validUserCount !== count($userIds)) {
                    $validator->errors()->add('user_ids', 'Some users do not belong to your tenant or are inactive');
                }
            }
        });
    }

    /**
     * Get the validated data with default empty array for user_ids
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Ensure user_ids is always an array (empty if not provided)
        if (!isset($validated['user_ids'])) {
            $validated['user_ids'] = [];
        }
        
        return $validated;
    }
}