<?php

return [
    App\Providers\AppServiceProvider::class,
    Nwidart\Modules\LaravelModulesServiceProvider::class,
    Modules\Tenant\App\Providers\TenantServiceProvider::class,
    Modules\User\App\Providers\UserServiceProvider::class,
    Modules\Auth\App\Providers\AuthServiceProvider::class,
    Modules\Teams\App\Providers\TeamsServiceProvider::class,
    Modules\TeamMembers\App\Providers\TeamMembersServiceProvider::class,
    Modules\TeamAvailability\App\Providers\TeamAvailabilityServiceProvider::class,
    Modules\Bookings\App\Providers\BookingsServiceProvider::class,
];
