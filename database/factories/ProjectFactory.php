<?php

namespace Database\Factories;

use App\Models\Entity;
use App\Models\Project;
use Carbon\Carbon;
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
            'name' => 'My accessibility project – ' . Carbon::parse($start)->format('F Y'),
            'start_date' => $start,
            'end_date' => $this->faker->dateTimeBetween('+1 months', '+6 months'),
            'published_at' => date('Y-m-d h:i:s', time()),
            'goals' => ['en' => 'Here’s a brief description of what we hope to accomplish in this consultation process.'],
            'impact' => ['en' => 'The outcomes of this project will impact existing and new customers who identify as having a disability, or who are support people for someone with a disability.'],
            'out_of_scope' => ['en' => 'Here are a few things that are not part of the scope of this project.'],
            'timeline' => ['en' => 'To be announced.'],
            'payment_terms' => ['en' => "Payment will be divided into three chunks:\n\n1. Upon confirmation of participation\n2. Halfway through the consultation\n3. At the end of the consultation\n"],
            'payment_negotiable' => $this->faker->boolean(),
            'virtual_consultation' => true,
            'existing_clients' => $this->faker->boolean(),
            'prospective_clients' => $this->faker->boolean(),
            'employees' => $this->faker->boolean(),
            'regions' => get_region_codes(['CA']),
            'min' => 20,
            'max' => 20,
            'anything_else' => ['en' => 'New consultants welcomed.'],
            'flexible_deadlines' => true,
            'flexible_breaks' => true,
        ];
    }
}
