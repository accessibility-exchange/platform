<?php

namespace Tests\RequestFactories;

use App\Models\RegulatedOrganization;
use Worksome\RequestFactories\RequestFactory;

class StoreProjectRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'projectable_id' => RegulatedOrganization::first()->id,
            'projectable_type' => RegulatedOrganization::class,
            'name' => ['en' => 'Test project - '.$this->faker->words(3, true)],
        ];
    }
}
