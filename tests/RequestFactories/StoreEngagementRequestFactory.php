<?php

namespace Tests\RequestFactories;

use App\Models\Project;
use Worksome\RequestFactories\RequestFactory;

use function Pest\Faker\fake;

class StoreEngagementRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'name' => ['en' => 'Workshop '.fake()->randomNumber(5)],
            'who' => 'individuals',
            'ideal_participants' => 25,
            'minimum_participants' => 15,
            'paid' => true,
        ];
    }
}
