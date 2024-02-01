<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccessSupport>
 */
class AccessSupportFactory extends Factory
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
            'in_person' => $this->faker->boolean(),
            'virtual' => $this->faker->boolean(),
            'documents' => $this->faker->boolean(),
            'anonymizable' => $this->faker->boolean(),
        ];
    }
}
