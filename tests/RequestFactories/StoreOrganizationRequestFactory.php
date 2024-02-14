<?php

namespace Tests\RequestFactories;

use App\Enums\OrganizationType;
use Worksome\RequestFactories\RequestFactory;

class StoreOrganizationRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(OrganizationType::class)->value,
            'name' => [
                'en' => 'Test Organization - '.$this->faker->unique()->words(3, true),
                'fr' => 'Organisation des tests - '.$this->faker->unique()->words(3, true),
            ],
        ];
    }
}
