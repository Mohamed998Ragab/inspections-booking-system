<?php 

namespace Modules\Tenant\App\Repositories;

use Modules\Tenant\App\Repositories\TenantRepositoryInterface;
use Modules\Tenant\App\Models\Tenant;

class TenantRepository implements TenantRepositoryInterface
{
    public function getAll()
    {
        return Tenant::all();
    }

    public function find($id)
    {
        return Tenant::findOrFail($id);
    }

    public function create(array $data)
    {
        return Tenant::create($data);
    }

    public function update(Tenant $tenant, array $data)
    {
        $tenant->update($data);
        return $tenant;
    }

    public function delete(Tenant $tenant)
    {
        $tenant->delete();
    }
}
