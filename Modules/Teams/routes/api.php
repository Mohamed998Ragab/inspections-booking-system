<?php

use Illuminate\Support\Facades\Route;
use Modules\Teams\App\Http\Controllers\TeamController;

Route::middleware(['auth:sanctum', 'tenant.scope', 'tenant.access', 'tenant.admin'])->prefix('v1')->group(function () {
    Route::get('teams/active', [TeamController::class, 'active'])->name('teams.active');
    Route::apiResource('teams', TeamController::class)->names('teams');
});
