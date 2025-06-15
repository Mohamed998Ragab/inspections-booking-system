<?php

use Illuminate\Support\Facades\Route;
use Modules\TeamAvailability\App\Http\Controllers\TeamAvailabilityController;

Route::middleware(['auth:sanctum', 'tenant.scope', 'tenant.access' ,'tenant.admin'])->group(function () {
    
    // Team Availability Routes
    Route::prefix('v1/teams/{team}')->group(function () {
        // Get all availability for a team
        Route::get('/availability', [TeamAvailabilityController::class, 'index']);
        
        // Get only active availability for a team
        Route::get('/availability/active', [TeamAvailabilityController::class, 'active']);
        
        // Get availability for specific day (0=Sunday, 6=Saturday)
        Route::get('/availability/day/{dayOfWeek}', [TeamAvailabilityController::class, 'forDay']);
        
        // Sync team availability (replace all)
        Route::post('/availability/sync', [TeamAvailabilityController::class, 'sync']);
    });
    
    // Individual availability management
    Route::prefix('v1/team-availability')->group(function () {
        // Create single availability record
        Route::post('/', [TeamAvailabilityController::class, 'store']);
        
        // Update single availability record
        Route::patch('/{id}', [TeamAvailabilityController::class, 'update']);
        
        // Delete single availability record
        Route::delete('/{id}', [TeamAvailabilityController::class, 'destroy']);
    });
});
