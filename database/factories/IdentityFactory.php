<?php

namespace Database\Factories;

use App\Enums\IdentityCluster;
use App\Models\Identity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Identity>
 */
class IdentityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->boolean(10) ? $this->faker->sentence() : null,
            'clusters' => $this->faker->randomElements(IdentityCluster::class, null),
        ];
    }
}
