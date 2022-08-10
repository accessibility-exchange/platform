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
            'sectors' => [Sector::pluck('id')->first()],
            'service_areas' => $this->faker->randomElements(get_region_codes(), 2),
            'working_languages' => $this->faker->randomElements(get_available_languages(true), 2),
            'social_links' => ['linked_in' => '', 'facebook' => '', 'twitter' => '', 'instagram' => ''],
            'contact_person_name' => $this->faker->name,
            'contact_person_email' => $this->faker->email,
            'contact_person_phone' => '',
            'preferred_contact_method' => 'email',
        ];
    }
}
