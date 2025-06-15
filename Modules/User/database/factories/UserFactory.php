<?php

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\User\App\Models\User;
use Modules\Tenant\App\Models\Tenant;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'), // Default password for all users
            'role' => $this->faker->randomElement(['admin', 'manager', 'inspector', 'customer']),
            'phone' => $this->faker->optional(0.7)->phoneNumber(),
            'is_active' => $this->faker->boolean(95), // 95% chance of being active
            'remember_token' => null,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function manager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'manager',
            'is_active' => true,
        ]);
    }

    public function inspector(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'inspector',
            'is_active' => true,
        ]);
    }

    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'customer',
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function forTenant(int $tenantId): static
    {
        return $this->state(fn (array $attributes) => [
            'tenant_id' => $tenantId,
        ]);
    }
}