<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MatchingStrategy>
 */
class MatchingStrategyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $matchable = $this->faker->randomElement([
            'App\Models\Engagement',
            'App\Models\Project',
            null,
        ]);

        if ($matchable) {
            return [
                'matchable_type' => $matchable,
                'matchable_id' => $matchable::factory(),
            ];
        }

        return [];
    }
}
