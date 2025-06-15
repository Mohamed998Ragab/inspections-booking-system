<?php

namespace Modules\Tenant\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tenant\App\Models\Tenant;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        $companyName = $this->faker->company();
        $slug = strtolower(str_replace([' ', '&', ',', '.'], ['-', 'and', '', ''], $companyName));
        
        return [
            'name' => $companyName,
            'slug' => $slug,
            'domain' => $this->faker->optional(0.7)->domainName(),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'trial_ends_at' => $this->faker->optional(0.3)->dateTimeBetween('now', '+30 days'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
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

    public function withDomain(): static
    {
        return $this->state(fn (array $attributes) => [
            'domain' => $this->faker->domainName(),
        ]);
    }

    public function onTrial(): static
    {
        return $this->state(fn (array $attributes) => [
            'trial_ends_at' => $this->faker->dateTimeBetween('now', '+30 days'),
        ]);
    }
}