<?php

use Illuminate\Support\Facades\Route;
use Modules\TeamMembers\App\Http\Controllers\TeamMembersController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('teammembers', TeamMembersController::class)->names('teammembers');
});
