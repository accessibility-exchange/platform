<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class UpdateOrganizationRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'name' => ['en' => $this->faker->lastName.' Foundation'],
            'about' => ['en' => $this->faker->paragraph],
            'region' => $this->faker->randomElement(get_region_codes()),
            'locality' => $this->faker->city,
            'service_areas' => $this->faker->randomElements(get_region_codes(), 2),
            'working_languages' => $this->faker->randomElements(get_available_languages(true), 2),
        ];
    }
}
