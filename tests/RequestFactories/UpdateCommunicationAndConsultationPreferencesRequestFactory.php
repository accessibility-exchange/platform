<?php

namespace Tests\RequestFactories;

use App\Enums\ContactPerson;
use App\Enums\EngagementFormat;
use Worksome\RequestFactories\RequestFactory;

class UpdateCommunicationAndConsultationPreferencesRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'preferred_contact_person' => ContactPerson::Me->value,
            'email' => $this->faker->safeEmail(),
            'preferred_contact_method' => 'email',
            'consulting_methods' => [EngagementFormat::Survey->value],
        ];
    }

    public function supportPerson(): static
    {
        return $this->state([
            'preferred_contact_person' => ContactPerson::SupportPerson->value,
            'email' => null,
            'support_person_name' => $this->faker->name(),
            'support_person_email' => $this->faker->safeEmail(),
        ]);
    }
}
