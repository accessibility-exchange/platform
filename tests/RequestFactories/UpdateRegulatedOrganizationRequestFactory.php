<?php

namespace Tests\RequestFactories;

use App\Models\Sector;
use Worksome\RequestFactories\RequestFactory;

class UpdateRegulatedOrganizationRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'name' => ['en' => $this->faker->lastName.' Foundation'],
            'about' => ['en' => $this->faker->paragraph],
            'region' => $this->faker->randomElement(get_region_codes()),
            'locality' => $this->faker->city,
            'sectors' => [Sector::first()->id],
            'service_areas' => $this->faker->randomElements(get_region_codes(), 2),
            'working_languages' => $this->faker->randomElements(get_available_languages(true), 2),
            'social_links' => ['linked_in' => '', 'facebook' => '', 'twitter' => '', 'instagram' => ''],
            'contact_person_name' => $this->faker->name,
            'contact_person_email' => $this->faker->email,
            'contact_person_phone' => phone('416-555-5555', 'CA')->formatForCountry('CA'),
            'preferred_contact_method' => 'email',
            'preferred_contact_language' => 'en',
        ];
    }
}
