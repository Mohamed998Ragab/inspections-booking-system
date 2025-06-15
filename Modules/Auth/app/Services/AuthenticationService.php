<?php

namespace Modules\Auth\App\Services;

use Illuminate\Support\Facades\Hash;
use Modules\Tenant\App\Models\Tenant;
use Modules\User\App\Models\User;
use Modules\Tenant\App\Services\TenantContextService;

class AuthenticationService
{
    protected $tenantContext;

    public function __construct(TenantContextService $tenantContext)
    {
        $this->tenantContext = $tenantContext;
    }

    public function login(array $credentials)
    {
        $currentTenant = $this->tenantContext->getCurrentTenant();

        // Tenant must be resolved at this point
        if (!$currentTenant) {
            return null; // Cannot login without tenant context
        }


        $query = User::where('email', $credentials['email'])
            ->where('is_active', true);


        // If tenant context is available, scope to that tenant
        if ($currentTenant) {
            $query->where('tenant_id', $currentTenant);
        }

        $user = $query->first();

        if ($user && Hash::check($credentials['password'], $user->password) && $user->is_active) {



            // Set tenant context if not already set
            if (!$currentTenant) {
                $this->tenantContext->setCurrentTenant($user->tenant_id);
            }

            return $user->createToken('auth_token')->plainTextToken;
        }

        return null;
    }

    public function register(array $data)
    {
        // Get tenant_id from context if not provided in data
        $tenantId = $data['tenant_id'] ?? $this->tenantContext->getCurrentTenant();

        if (!$tenantId) {
            throw new \Exception('Tenant context not found for registration');
        }

        $user = User::create([
            'tenant_id' => $tenantId,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'customer',
            'phone' => $data['phone'] ?? null,
        ]);

        // Set tenant context
        $this->tenantContext->setCurrentTenant($user->tenant_id);

        return $user;
    }

    public function logout($user)
    {
        try {
            $currentTenant = $this->tenantContext->getCurrentTenant();

            // Double-check tenant context is properly set
            if (!$currentTenant) {
                // \Log::warning('Logout attempted without tenant context', ['user_id' => $user->id]);
                return false;
            }

            // Ensure user belongs to current tenant
            if ($user->tenant_id !== $currentTenant) {
                // \Log::warning('Logout attempted by user from different tenant', [
                //     'user_id' => $user->id,
                //     'user_tenant' => $user->tenant_id,
                //     'current_tenant' => $currentTenant
                // ]);
                return false;
            }

            // Additional security: Verify the token belongs to the user in the current tenant
            $token = $user->currentAccessToken();
            if (!$token) {
                // \Log::warning('Logout attempted without valid token', ['user_id' => $user->id]);
                return false;
            }

            // Delete the token
            $token->delete();

            // \Log::info('User logged out successfully', [
            //     'user_id' => $user->id,
            //     'tenant_id' => $currentTenant
            // ]);

            return true;
        } catch (\Exception $e) {
            // \Log::error('Logout failed', [
            //     'user_id' => $user->id ?? 'unknown',
            //     'error' => $e->getMessage()
            // ]);
            return false;
        }
    }
}
