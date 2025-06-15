<?php

use Illuminate\Support\Facades\Route;
use Modules\Tenant\App\Http\Controllers\TenantController;

Route::middleware(['auth:sanctum', 'superadmin'])->prefix('v1')->group(function () {
    Route::apiResource('tenants', TenantController::class)->names('tenants');
});
