<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobPost>
 */
class JobPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'requirements' => fake()->paragraph,
            'description' => fake()->text,
            'min_salary' => fake()->numberBetween(30000, 50000),
            'max_salary' => fake()->numberBetween(50001, 80000),
            'city' => fake()->city,
            'country' => fake()->country,
            'min_exp_years' => fake()->numberBetween(1, 3),
            'max_exp_years' => fake()->numberBetween(4, 10),
            'expires_at' => Carbon::now()->addDays(fake()->numberBetween(30, 180)),
            'type' => fake()->randomElement(['full-time', 'part-time', 'contract']),
            'remote_type' => fake()->randomElement(['on-site', 'hybrid', 'remote']),
            'experience_level' => fake()->randomElement(['entry_level', 'associate', 'mid-senior', 'director', 'executive']),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
