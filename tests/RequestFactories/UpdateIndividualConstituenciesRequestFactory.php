<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class UpdateIndividualConstituenciesRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'disability_and_deaf' => 1,
            'lived_experiences' => [],
            'base_disability_type' => 'specific_disabilities',
            'has_other_disability_connection' => 1,
            'other_disability_connection' => ['en' => 'Something not listed'],
            'area_types' => [],
            'has_indigenous_identity_connections' => 0,
            'refugees_and_immigrants' => 0,
            'has_gender_and_sexuality_connections' => 0,
            'has_age_bracket_connections' => 0,
            'has_ethnoracial_identity_connections' => 1,
            'has_other_ethnoracial_identity_connection' => 1,
            'other_ethnoracial_identity_connection' => ['en' => 'Something not listed'],
            'language_connections' => ['en', 'fr'],
            'connection_lived_experience' => 'yes-some',
        ];
    }
}
