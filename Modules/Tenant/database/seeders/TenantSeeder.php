<?php

namespace Modules\Tenant\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Tenant\App\Models\Tenant;
use Modules\User\App\Models\User;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // Create specific tenants for different inspection types
        $tenants = [
            [
                'name' => 'HomeSafe Inspections',
                'slug' => 'homesafe',
                'domain' => 'homesafe.inspections.com',
                'is_active' => true,
            ],
            [
                'name' => 'AutoCheck Vehicle Inspections',
                'slug' => 'autocheck',
                'domain' => 'autocheck.inspections.com',
                'is_active' => true,
            ],
            [
                'name' => 'SafeBuild Construction Inspections',
                'slug' => 'safebuild',
                'domain' => 'safebuild.inspections.com',
                'is_active' => true,
            ],
            [
                'name' => 'QuickInspect Services',
                'slug' => 'quickinspect',
                'domain' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Elite Property Inspectors',
                'slug' => 'elite-property',
                'domain' => 'elite.property.com',
                'is_active' => true,
                'trial_ends_at' => now()->addDays(15),
            ]
        ];

        foreach ($tenants as $tenantData) {
            $tenant = Tenant::create($tenantData);
            $this->createUsersForTenant($tenant);
        }

        // Create additional random tenants
        Tenant::factory()
            ->count(5)
            ->create()
            ->each(function ($tenant) {
                $this->createUsersForTenant($tenant);
            });
    }

    private function createUsersForTenant(Tenant $tenant): void
    {
        // Create 1 Admin per tenant
        User::factory()
            ->admin()
            ->forTenant($tenant->id)
            ->create([
                'name' => 'Admin User - ' . $tenant->name,
                'email' => 'admin@' . $tenant->slug . '.com',
                'password' => Hash::make('admin123'),
            ]);

        // Create 1-2 Managers per tenant
        User::factory()
            ->manager()
            ->forTenant($tenant->id)
            ->count(rand(1, 2))
            ->create();

        // Create 3-8 Inspectors per tenant
        User::factory()
            ->inspector()
            ->forTenant($tenant->id)
            ->count(rand(1, 2))
            ->create();

        // Create 10-25 Customers per tenant
        User::factory()
            ->customer()
            ->forTenant($tenant->id)
            ->count(rand(1, 2))
            ->create();

        // Create some inactive users
        User::factory()
            ->inactive()
            ->forTenant($tenant->id)
            ->count(rand(1, 2))
            ->create();
    }
}