<?php

use Illuminate\Support\Facades\Route;
use Modules\User\App\Http\Controllers\UserController;

Route::middleware(['auth:sanctum', 'tenant.scope', 'tenant.access', 'tenant.admin'])->prefix('v1')->group(function () {
    Route::apiResource('users', UserController::class)->names('users');
    // Route::patch('users/{user}', [UserController::class, 'update']);
});
