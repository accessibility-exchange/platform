<?php

namespace Tests\RequestFactories;

use App\Models\Project;
use function Pest\Faker\faker;
use Worksome\RequestFactories\RequestFactory;

class StoreEngagementRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'name' => ['en' => 'Workshop '.faker()->randomNumber(5)],
            'who' => 'individuals',
            'ideal_participants' => 25,
            'minimum_participants' => 15,
            'paid' => true,
        ];
    }
}
