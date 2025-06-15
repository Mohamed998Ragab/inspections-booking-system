<?php

namespace Modules\Tenant\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Tenant\App\Services\TenantServiceInterface;
use Modules\Tenant\App\Models\Tenant;
use Modules\Tenant\App\Requests\StoreTenantRequest;
use Modules\Tenant\App\Requests\UpdateTenantRequest;
use Modules\Tenant\App\Transformers\TenantResource;

class TenantController
{
    protected $tenantService;

    public function __construct(TenantServiceInterface $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function index()
    {
        $tenants = $this->tenantService->getAll();
        return TenantResource::collection($tenants);
    }

    public function store(StoreTenantRequest $request)
    {
        $tenant = $this->tenantService->create($request->validated());
        return new TenantResource($tenant);
    }

    public function show($id)
    {
        $tenant = $this->tenantService->find($id);
        return new TenantResource($tenant);
    }

    public function update(UpdateTenantRequest $request, $id)
    {
        $tenant = $this->tenantService->find($id);
        $updatedTenant = $this->tenantService->update($tenant, $request->validated());
        return new TenantResource($updatedTenant);
    }

    public function destroy($id)
    {
        $tenant = $this->tenantService->find($id);
        $this->tenantService->delete($tenant);
        return response()->json(['message' => 'Tenant deleted successfully']);
    }
}
