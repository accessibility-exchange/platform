<?php

namespace Database\Factories;

use App\Models\MatchingStrategy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Criterion>
 */
class CriterionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $criteriable = $this->faker->randomElement([
            'App\Models\LivedExperience',
            'App\Models\Constituency',
            // TODO: Region, gender, age bracket, etc
        ]);

        return [
            'matching_strategy_id' => MatchingStrategy::factory(),
            'criteriable_type' => $criteriable,
            'criteriable_id' => $criteriable::pluck('id')->first(),
            'weight' => $this->faker->randomFloat(2, 0, 1),
        ];
    }
}
