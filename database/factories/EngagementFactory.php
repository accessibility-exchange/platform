<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class EngagementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'name' => ['en' => 'Workshop'],
            'format' => 'workshop',
            'ideal_participants' => 25,
            'minimum_participants' => 15,
            'paid' => true,
        ];
    }
}
