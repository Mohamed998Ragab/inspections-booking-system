<?php

use Illuminate\Support\Facades\Route;

use Modules\Auth\App\Http\Controllers\LoginController;
use Modules\Auth\App\Http\Controllers\RegisterController;
use Modules\Auth\App\Http\Controllers\LogoutController;

// Public routes (no auth required)
Route::middleware(['tenant.scope'])->prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
});

// Protected routes (auth + tenant access required)
Route::middleware(['auth:sanctum', 'tenant.scope', 'tenant.access'])->prefix('v1')->group(function () {
    Route::post('logout', [LogoutController::class, 'logout']);
});
