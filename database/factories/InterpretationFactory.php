<?php

namespace Database\Factories;

use App\Models\Interpretation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Interpretation>
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
            'video' => [
                'asl' => 'https://vimeo.com/766454375/276fbdc032',
                'lsq' => 'https://vimeo.com/766455246/ccd2109379',
            ],
        ];
    }
}
