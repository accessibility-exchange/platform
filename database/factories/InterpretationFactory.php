<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interpretation>
 */
class InterpretationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'route' => 'welcome',
            'route_has_params' => false,
            'video' => [
                'ase' => 'https://vimeo.com/766454375',
                'fcs' => 'https://vimeo.com/766455246',
            ],
        ];
    }
}
