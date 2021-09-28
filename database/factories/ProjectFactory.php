<?php

namespace Database\Factories;

use App\Models\Entity;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start = $this->faker->dateTimeBetween('-6 months', '-1 months');

        return [
            'entity_id' => Entity::factory(),
            'name' => 'My accessibility project',
            'start_date' => $start,
            'end_date' => $this->faker->dateTimeBetween('+1 months', '+6 months'),
            'published_at' => date('Y-m-d h:i:s', time()),
        ];
    }
}
