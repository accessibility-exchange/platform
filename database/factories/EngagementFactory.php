<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class EngagementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => ['en' => 'Focus group'],
            'project_id' => Project::factory(),
            'recruitment' => 'automatic',
        ];
    }
}
