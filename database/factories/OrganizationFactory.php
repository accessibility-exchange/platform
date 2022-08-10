<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'type' => $this->faker->randomElement(['representative', 'support', 'civil-society']),
            'languages' => ['en', 'fr', 'ase', 'fcs'],
            'working_languages' => ['en', 'fr'],
        ];
    }
}
