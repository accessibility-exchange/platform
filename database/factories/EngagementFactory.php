<?php

namespace Database\Factories;

use App\Enums\EngagementFormat;
use App\Enums\EngagementRecruitment;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class EngagementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'published_at' => date('Y-m-d h:i:s', time()),
            'project_id' => Project::factory(),
            'name' => ['en' => 'Workshop'],
            'languages' => config('locales.supported'),
            'who' => 'individuals',
            'description' => ['en' => 'About this engagement'],
            'format' => EngagementFormat::Workshop->value,
            'recruitment' => EngagementRecruitment::OpenCall->value,
            'ideal_participants' => 25,
            'minimum_participants' => 15,
            'paid' => true,
        ];
    }
}
