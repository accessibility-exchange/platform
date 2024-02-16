<?php

namespace Tests\RequestFactories;

use App\Enums\ContactPerson;
use App\Enums\MeetingType;
use Worksome\RequestFactories\RequestFactory;

class UpdateIndividualCommunicationAndConsultationPreferencesRequestFactory extends RequestFactory
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
            'preferred_contact_method' => 'email',
            'meeting_types' => [MeetingType::InPerson->value],
        ];
    }
}
