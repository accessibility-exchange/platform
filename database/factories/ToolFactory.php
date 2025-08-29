<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tool>
 */
class ToolFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => ['en' => $this->faker->words(3, true)],
            'description' => ['en' => $this->faker->sentence()],
        ];
    }
}
