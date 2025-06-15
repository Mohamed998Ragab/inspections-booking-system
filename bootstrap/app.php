<?php

use App\Http\Middleware\Role\EnsureSuperAdmin;
use App\Http\Middleware\Role\EnsureTenantAdmin;
use App\Http\Middleware\Tenant\EnsureTenantAccess;
use App\Http\Middleware\Tenant\TenantScopeMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'tenant.scope' => TenantScopeMiddleware::class,
            'tenant.access' => EnsureTenantAccess::class,
            'superadmin' => EnsureSuperAdmin::class,
            'tenant.admin' => EnsureTenantAdmin::class,
        ]);

        $middleware->priority([
            TenantScopeMiddleware::class,
            EnsureTenantAccess::class,
            EnsureSuperAdmin::class,
            EnsureTenantAdmin::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
