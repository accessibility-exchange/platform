<?php

namespace Tests\RequestFactories;

use App\Enums\ContactMethod;
use App\Enums\ContactPerson;
use App\Enums\EngagementFormat;
use Worksome\RequestFactories\RequestFactory;

class UpdateCommunicationAndConsultationPreferencesRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'preferred_contact_person' => ContactPerson::Me->value,
            'email' => $this->faker->unique->email(),
            'phone' => phone('416-555-5555', 'CA')->formatForCountry('CA'),
            'support_person_name' => $this->faker->name(),
            'support_person_email' => $this->faker->unique->email(),
            'support_person_phone' => phone('416-555-5555', 'CA')->formatForCountry('CA'),
            'preferred_contact_method' => ContactMethod::Email->value,
            'consulting_methods' => [EngagementFormat::Survey->value],
        ];
    }
}
