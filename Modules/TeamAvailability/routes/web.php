<?php

use Illuminate\Support\Facades\Route;
use Modules\TeamAvailability\App\Http\Controllers\TeamAvailabilityController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('teamavailabilities', TeamAvailabilityController::class)->names('teamavailability');
});
