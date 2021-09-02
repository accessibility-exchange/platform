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
        return [
            'name' => 'My accessibility project',
            'entity_id' => Entity::factory(),
            'start_date' => $this->faker->dateTimeBetween('-6 months', '-1 months'),
            'end_date' => $this->faker->dateTimeBetween('+1 months', '+6 months'),
        ];
    }
}
