<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
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
