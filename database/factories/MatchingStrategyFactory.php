<?php

namespace Database\Factories;

use App\Models\MatchingStrategy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MatchingStrategy>
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
