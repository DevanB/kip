<?php

namespace Database\Factories;

use App\Models\DrTest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DrTestPhase>
 */
class DrTestPhaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startedAt = fake()->dateTimeBetween('-6 months', 'now');
        $durationMinutes = fake()->numberBetween(5, 60);
        $finishedAt = (clone $startedAt)->modify("+{$durationMinutes} minutes");

        return [
            'dr_test_id' => DrTest::factory(),
            'title' => fake()->randomElement([
                'Initial Assessment',
                'System Failover',
                'Data Validation',
                'Service Restoration',
                'User Verification',
                'Final Testing',
                'Documentation',
                'Rollback Preparation',
            ]),
            'started_at' => $startedAt,
            'finished_at' => $finishedAt,
            'duration_minutes' => $durationMinutes,
        ];
    }
}
