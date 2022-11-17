<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\RegulatedOrganization;
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
        $end = $this->faker->dateTimeBetween('+1 months', '+6 months');

        return [
            'projectable_type' => 'App\Models\RegulatedOrganization',
            'projectable_id' => RegulatedOrganization::factory(),
            'name' => ['en' => 'My accessibility project – '.Carbon::parse($start)->format('F Y')],
            'languages' => ['en', 'fr', 'asl', 'lsq'],
            'regions' => ['AB'],
            'start_date' => $start,
            'end_date' => $end,
            'outcome_analysis' => ['internal'],
            'contact_person_email' => $this->faker->email,
            'contact_person_name' => $this->faker->name,
            'published_at' => date('Y-m-d h:i:s', time()),
            'goals' => ['en' => 'Here’s a brief description of what we hope to accomplish in this consultation process.'],
            'scope' => ['en' => 'The outcomes of this project will impact existing and new customers who identify as having a disability, or who are support people for someone with a disability.'],
            'out_of_scope' => ['en' => 'Here are a few things that are not part of the scope of this project.'],
        ];
    }
}
