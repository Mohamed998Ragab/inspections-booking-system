<?php

use Illuminate\Support\Facades\Route;
use Modules\TeamMembers\App\Http\Controllers\TeamMembersController;

Route::middleware(['auth:sanctum', 'tenant.scope', 'tenant.access', 'tenant.admin'])->prefix('v1')->group(function () {
    Route::post('teams/{team}/members/sync', [TeamMembersController::class, 'sync'])->name('team-members.sync');

    Route::get('teams/{team}/members', [TeamMembersController::class, 'index'])->name('team-members.index');
    // Route::post('teams/{team}/members', [TeamMembersController::class, 'store'])->name('team-members.store');
    // Route::delete('teams/{team}/members', [TeamMembersController::class, 'destroy'])->name('team-members.destroy');
});
