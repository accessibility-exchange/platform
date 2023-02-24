<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Module>
 */
class ModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => ['en' => $this->faker->words(3, true)],
            'description' => ['en' => $this->faker->sentence(4)],
            'introduction' => ['en' => $this->faker->sentence(5)],
            'video' => [
                'en' => 'https://vimeo.com/766454375',
                'fr' => 'https://vimeo.com/766455246',
            ],
        ];
    }
}
