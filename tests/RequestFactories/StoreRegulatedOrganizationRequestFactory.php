<?php

namespace Tests\RequestFactories;

use App\Enums\RegulatedOrganizationType;
use Worksome\RequestFactories\RequestFactory;

class StoreRegulatedOrganizationRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(RegulatedOrganizationType::class)->value,
            'name' => [
                'en' => 'Test Regulated Organization - '.$this->faker->unique()->words(3, true),
                'fr' => 'Organisme réglementé par les tests - '.$this->faker->unique()->words(3, true),
            ],
        ];
    }
}
