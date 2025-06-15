<?php 

namespace Modules\Tenant\App\Services;

use Modules\Tenant\App\Models\Tenant;

interface TenantServiceInterface
{
    public function getAll();
    public function find($id);
    public function create(array $data);
    public function update(Tenant $tenant, array $data);
    public function delete(Tenant $tenant);
}