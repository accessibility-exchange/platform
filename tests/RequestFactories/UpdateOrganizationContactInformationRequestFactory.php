<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class UpdateOrganizationContactInformationRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'contact_person_name' => $this->faker->name(),
            'contact_person_email' => $this->faker->email(),
            'contact_person_phone' => phone('416-555-5555', 'CA')->formatForCountry('CA'),
            'preferred_contact_method' => $this->faker->randomElement(['email', 'phone']),
            'preferred_contact_language' => $this->faker()->randomElement(get_supported_locales(false)),
        ];
    }
}
