<?php

use Illuminate\Support\Facades\Route;
use Modules\Bookings\App\Http\Controllers\BookingsController;

Route::middleware(['auth:sanctum', 'tenant.scope', 'tenant.access', 'tenant.admin'])->prefix('v1')->group(function () {

    Route::get('teams/{id}/generate-slots', [BookingsController::class, 'generateSlots']);
    
    // Bookings
    Route::get('bookings', [BookingsController::class, 'index']);
    Route::post('bookings', [BookingsController::class, 'store'])->withoutMiddleware('tenant.admin');
    Route::delete('bookings/{id}', [BookingsController::class, 'destroy']);
});
