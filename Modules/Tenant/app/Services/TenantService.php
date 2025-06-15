<?php

namespace Modules\Tenant\App\Services;

use Modules\Tenant\App\Repositories\TenantRepositoryInterface;
use Modules\Tenant\App\Services\TenantServiceInterface;
use Modules\Tenant\App\Models\Tenant;

class TenantService implements TenantServiceInterface
{
    protected $tenantRepository;

    public function __construct(TenantRepositoryInterface $tenantRepository)
    {
        $this->tenantRepository = $tenantRepository;
    }

    public function getAll()
    {
        return $this->tenantRepository->getAll();
    }

    public function find($id)
    {
        return $this->tenantRepository->find($id);
    }

    public function create(array $data)
    {
        $data['is_active'] = true; // Default value
        return $this->tenantRepository->create($data);
    }

    public function update(Tenant $tenant, array $data)
    {
        return $this->tenantRepository->update($tenant, $data);
    }

    public function delete(Tenant $tenant)
    {
        $this->tenantRepository->delete($tenant);
    }
}