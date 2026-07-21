<?php

namespace Database\Factories;

use App\Models\Impact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Impact>
 */
class ImpactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->boolean(10) ? $this->faker->sentence() : null,
        ];
    }
}
