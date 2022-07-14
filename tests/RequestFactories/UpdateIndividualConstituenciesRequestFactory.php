<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class UpdateIndividualConstituenciesRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'lived_experiences' => [],
            'base_disability_type' => 'specific_disabilities',
            'other_disability' => 1,
            'other_disability_type' => ['en' => 'Something not listed'],
            'area_types' => [],
            'has_indigenous_identities' => 0,
            'refugees_and_immigrants' => 0,
            'has_gender_and_sexual_identities' => 0,
            'has_age_brackets' => 0,
            'has_ethnoracial_identities' => 1,
            'other_ethnoracial' => 1,
            'other_ethnoracial_identity_connection' => ['en' => 'Something not listed'],
            'constituent_languages' => ['en', 'fr'],
            'connection_lived_experience' => 'yes-some',
        ];
    }
}
