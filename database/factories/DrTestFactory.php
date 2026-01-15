<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DrTest>
 */
class DrTestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'test_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'rto_minutes' => fake()->numberBetween(30, 180),
            'rpo_minutes' => fake()->numberBetween(15, 120),
            'notes' => fake()->optional(0.7)->sentence(),
        ];
    }
}
