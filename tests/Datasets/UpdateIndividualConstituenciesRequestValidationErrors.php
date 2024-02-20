<?php

use App\Enums\BaseDisabilityType;

dataset('updateIndividualConstituenciesRequestValidationErrors', function () {
    return [
        'Disability and deaf is missing' => [
            'state' => [
                'disability_and_deaf' => null,
                'lived_experience_connections' => null,
            ],
            'errors' => fn () => ['disability_and_deaf' => __('You must select at least one option for “Can you connect to people with disabilities, Deaf persons, and/or their supporters?”')],
        ],
        'Disability and deaf is not a boolean' => [
            'state' => ['disability_and_deaf' => 123],
            'errors' => fn () => ['disability_and_deaf' => __('validation.boolean', ['attribute' => __('Disability and/or Deaf identity')])],
        ],
        'Lived experience connections is missing' => [
            'state' => [
                'disability_and_deaf' => false,
                'lived_experience_connections' => null,
            ],
            'errors' => fn () => ['lived_experience_connections' => __('You must select at least one option for “Can you connect to people with disabilities, Deaf persons, and/or their supporters?”')],
        ],
        'Lived experience connections is not an array' => [
            'state' => [
                'disability_and_deaf' => true,
                'lived_experience_connections' => false,
            ],
            'errors' => fn () => ['lived_experience_connections' => __('validation.array', ['attribute' => __('lived experience connections')])],
        ],
        'Lived experience connection is invalid' => [
            'state' => [
                'disability_and_deaf' => true,
                'lived_experience_connections' => [1000000],
            ],
            'errors' => fn () => ['lived_experience_connections.0' => __('validation.exists', ['attribute' => __('lived experience connections')])],
        ],
        'Base disability type is missing' => [
            'state' => [
                'disability_and_deaf' => true,
                'base_disability_type' => null,
            ],
            'errors' => fn () => ['base_disability_type' => __('You must select one option for “Please select people with disabilities that you can connect to”.')],
        ],
        'Base disability type is invalid' => [
            'state' => [
                'disability_and_deaf' => true,
                'base_disability_type' => 'other',
            ],
            'errors' => fn () => ['base_disability_type' => __('validation.exists', ['attribute' => __('disability type')])],
        ],
        'Disability and deaf connections is not an array' => [
            'state' => [
                'disability_and_deaf' => true,
                'disability_and_deaf_connections' => true,
                'base_disability_type' => BaseDisabilityType::SpecificDisabilities->value,
            ],
            'errors' => fn () => ['disability_and_deaf_connections' => __('validation.array', ['attribute' => __('disability and deaf connections')])],
        ],
        'Disability and deaf connection is invalid' => [
            'state' => [
                'disability_and_deaf' => true,
                'disability_and_deaf_connections' => [1000000],
                'base_disability_type' => BaseDisabilityType::SpecificDisabilities->value,
            ],
            'errors' => fn () => ['disability_and_deaf_connections.0' => __('validation.exists', ['attribute' => __('disability and deaf connections')])],
        ],
        'Has other disability connection is not a boolean' => [
            'state' => ['has_other_disability_connection' => 123],
            'errors' => fn () => ['has_other_disability_connection' => __('validation.boolean', ['attribute' => __('has other disability connection')])],
        ],
        'Other disability connection is missing' => [
            'state' => [
                'other_disability_connection' => null,
                'has_other_disability_connection' => true,
                'base_disability_type' => BaseDisabilityType::SpecificDisabilities->value,
            ],
            'errors' => fn () => [
                'other_disability_connection.en' => __('There is no disability type filled in under "something else". Please fill this in.'),
                'other_disability_connection.fr' => __('There is no disability type filled in under "something else". Please fill this in.'),
            ],
        ],
        'Other disability connection is missing required translation' => [
            'state' => [
                'other_disability_connection' => ['es' => 'otra conexión de discapacidad'],
                'has_other_disability_connection' => true,
                'base_disability_type' => BaseDisabilityType::SpecificDisabilities->value,
            ],
            'errors' => fn () => [
                'other_disability_connection.en' => __('There is no disability type filled in under "something else". Please fill this in.'),
                'other_disability_connection.fr' => __('There is no disability type filled in under "something else". Please fill this in.'),
            ],
            'without' => ['other_disability_connection'],
        ],
        'Other disability connection is not an array' => [
            'state' => [
                'other_disability_connection' => 123,
                'has_other_disability_connection' => true,
                'base_disability_type' => BaseDisabilityType::SpecificDisabilities->value,
            ],
            'errors' => fn () => ['other_disability_connection' => __('validation.array', ['attribute' => __('other disability connection')])],
        ],
        'Other disability connection is not a string' => [
            'state' => [
                'other_disability_connection' => ['en' => 123],
                'has_other_disability_connection' => true,
                'base_disability_type' => BaseDisabilityType::SpecificDisabilities->value,
            ],
            'errors' => fn () => ['other_disability_connection.en' => __('validation.string', ['attribute' => __('other disability connection')])],
        ],
        'Area type connections is missing' => [
            'state' => ['area_type_connections' => null],
            'errors' => fn () => ['area_type_connections' => __('You must select at least one option for “Where do the people that you can connect to come from?”')],
        ],
        'Area type connections is not an array' => [
            'state' => ['area_type_connections' => 123],
            'errors' => fn () => ['area_type_connections' => __('validation.array', ['attribute' => __('area type connections')])],
        ],
        'Area type connection is invalid' => [
            'state' => ['area_type_connections' => [1000000]],
            'errors' => fn () => ['area_type_connections.0' => __('validation.exists', ['attribute' => __('area type connections')])],
        ],
        'Has indigenous connections is missing' => [
            'state' => ['has_indigenous_connections' => null],
            'errors' => fn () => ['has_indigenous_connections' => __('You must select one option for “Can you connect to people who are First Nations, Inuit, or Métis?”')],
        ],
        'Has indigenous connections is not a boolean' => [
            'state' => ['has_indigenous_connections' => 123],
            'errors' => fn () => ['has_indigenous_connections' => __('validation.boolean', ['attribute' => __('has indigenous connections')])],
        ],
        'Indigenous connections is missing' => [
            'state' => [
                'has_indigenous_connections' => true,
                'indigenous_connections' => null,
            ],
            'errors' => fn () => ['indigenous_connections' => __('You must select at least one Indigenous group you can connect to.')],
        ],
        'Indigenous connections is not an array' => [
            'state' => [
                'has_indigenous_connections' => true,
                'indigenous_connections' => false,
            ],
            'errors' => fn () => ['indigenous_connections' => __('validation.array', ['attribute' => __('indigenous connections')])],
        ],
        'Indigenous connection is invalid' => [
            'state' => [
                'has_indigenous_connections' => true,
                'indigenous_connections' => [1000000],
            ],
            'errors' => fn () => ['indigenous_connections.0' => __('validation.exists', ['attribute' => __('indigenous connections')])],
        ],
        'Refugees and immigrants is missing' => [
            'state' => ['refugees_and_immigrants' => null],
            'errors' => fn () => ['refugees_and_immigrants' => __('You must select one option for “Can you connect to refugees and/or immigrants?”')],
        ],
        'Refugees and immigrants is not a boolean' => [
            'state' => ['refugees_and_immigrants' => 123],
            'errors' => fn () => ['refugees_and_immigrants' => __('validation.boolean', ['attribute' => __('Refugees and/or immigrants')])],
        ],
        'Has gender and sexuality connections is missing' => [
            'state' => ['has_gender_and_sexuality_connections' => null],
            'errors' => fn () => ['has_gender_and_sexuality_connections' => __('You must select one option for “Can you connect to people who are marginalized based on gender or sexual identity?”')],
        ],
        'Has gender and sexuality connections is not a boolean' => [
            'state' => ['has_gender_and_sexuality_connections' => 123],
            'errors' => fn () => ['has_gender_and_sexuality_connections' => __('validation.boolean', ['attribute' => __('has gender and sexuality connections')])],
        ],
        'Gender and sexuality connections is missing' => [
            'state' => [
                'gender_and_sexuality_connections' => null,
                'has_gender_and_sexuality_connections' => true,
                'nb_gnc_fluid_identity' => null,
            ],
            'errors' => fn () => [
                'gender_and_sexuality_connections' => __('You must select at least one gender or sexual identity group you can connect to.'),
                'nb_gnc_fluid_identity' => __('You must select at least one gender or sexual identity group you can connect to.'),
            ],
        ],
        'Gender and sexuality connections is not an array' => [
            'state' => [
                'gender_and_sexuality_connections' => 123,
                'has_gender_and_sexuality_connections' => true,
                'nb_gnc_fluid_identity' => null,
            ],
            'errors' => fn () => ['gender_and_sexuality_connections' => __('validation.array', ['attribute' => __('gender and sexuality connections')])],
        ],
        'Gender and sexuality connection is invalid' => [
            'state' => [
                'gender_and_sexuality_connections' => [100000],
                'has_gender_and_sexuality_connections' => true,
                'nb_gnc_fluid_identity' => null,
            ],
            'errors' => fn () => ['gender_and_sexuality_connections.0' => __('validation.exists', ['attribute' => __('gender and sexuality connections')])],
        ],
        'Non-binary gender non-conforming fluid identity is missing' => [
            'state' => [
                'gender_and_sexuality_connections' => [],
                'has_gender_and_sexuality_connections' => true,
                'nb_gnc_fluid_identity' => null,
            ],
            'errors' => fn () => [
                'gender_and_sexuality_connections' => __('You must select at least one gender or sexual identity group you can connect to.'),
                'nb_gnc_fluid_identity' => __('You must select at least one gender or sexual identity group you can connect to.'),
            ],
        ],
        'Non-binary gender non-conforming fluid identity is not a boolean' => [
            'state' => [
                'gender_and_sexuality_connections' => [],
                'has_gender_and_sexuality_connections' => true,
                'nb_gnc_fluid_identity' => 123,
            ],
            'errors' => fn () => ['nb_gnc_fluid_identity' => __('validation.boolean', ['attribute' => __('Non-binary/Gender non-conforming/Gender fluid identity')])],
        ],
        'Has age bracket connections is missing' => [
            'state' => ['has_age_bracket_connections' => null],
            'errors' => fn () => ['has_age_bracket_connections' => __('You must select one option for “Can you connect to a specific age group or groups?”')],
        ],
        'Has age bracket connections is not a boolean' => [
            'state' => ['has_age_bracket_connections' => 123],
            'errors' => fn () => ['has_age_bracket_connections' => __('validation.boolean', ['attribute' => __('has age bracket connections')])],
        ],
        'Age bracket connections is missing' => [
            'state' => [
                'has_age_bracket_connections' => true,
                'age_bracket_connections' => null,
            ],
            'errors' => fn () => ['age_bracket_connections' => __('You must select at least one age group you can connect to.')],
        ],
        'Age bracket connections is not an array' => [
            'state' => [
                'has_age_bracket_connections' => true,
                'age_bracket_connections' => false,
            ],
            'errors' => fn () => ['age_bracket_connections' => __('validation.array', ['attribute' => __('age bracket connections')])],
        ],
        'Age bracket connection is invalid' => [
            'state' => [
                'has_age_bracket_connections' => true,
                'age_bracket_connections' => [100000],
            ],
            'errors' => fn () => ['age_bracket_connections.0' => __('validation.exists', ['attribute' => __('age bracket connections')])],
        ],
        'Has ethnoracial identity connections is missing' => [
            'state' => ['has_ethnoracial_identity_connections' => null],
            'errors' => fn () => ['has_ethnoracial_identity_connections' => __('You must select one option for “Can you connect to people with a specific ethno-racial identity or identities?”')],
        ],
        'Has ethnoracial identity connections is not a boolean' => [
            'state' => ['has_ethnoracial_identity_connections' => 123],
            'errors' => fn () => ['has_ethnoracial_identity_connections' => __('validation.boolean', ['attribute' => __('has ethnoracial identity connections')])],
        ],
        'Ethnoracial identity connections is not an array' => [
            'state' => [
                'ethnoracial_identity_connections' => true,
                'has_ethnoracial_identity_connections' => true,
                'has_other_ethnoracial_identity_connection' => null,
            ],
            'errors' => fn () => ['ethnoracial_identity_connections' => __('validation.array', ['attribute' => __('ethnoracial identity connections')])],
        ],
        'Ethnoracial identity connection is invalid' => [
            'state' => [
                'ethnoracial_identity_connections' => [1000000],
                'has_ethnoracial_identity_connections' => true,
                'has_other_ethnoracial_identity_connection' => null,
            ],
            'errors' => fn () => ['ethnoracial_identity_connections.0' => __('validation.exists', ['attribute' => __('ethnoracial identity connections')])],
        ],
        'Has other ethnoracial identity connection is not a boolean' => [
            'state' => ['has_other_ethnoracial_identity_connection' => 123],
            'errors' => fn () => ['has_other_ethnoracial_identity_connection' => __('validation.boolean', ['attribute' => __('has other ethnoracial identity connection')])],
        ],
        'Other ethnoracial identity connections is not an array' => [
            'state' => [
                'other_ethnoracial_identity_connection' => 123,
                'has_other_ethnoracial_identity_connection' => true,
                'has_ethnoracial_identity_connections' => true,
            ],
            'errors' => fn () => [
                'other_ethnoracial_identity_connection' => __('validation.array', ['attribute' => __('other ethnoracial identity connection')]),
                'other_ethnoracial_identity_connection.en' => __('There is no ethnoracial identity filled in under "something else". Please fill this in.'),
                'other_ethnoracial_identity_connection.fr' => __('There is no ethnoracial identity filled in under "something else". Please fill this in.'),
            ],
        ],
        'Other ethnoracial identity connection is not a string' => [
            'state' => [
                'other_ethnoracial_identity_connection' => ['en' => false],
                'has_other_ethnoracial_identity_connection' => true,
                'has_ethnoracial_identity_connections' => true,
            ],
            'errors' => fn () => ['other_ethnoracial_identity_connection.en' => __('validation.string', ['attribute' => __('other ethnoracial identity connection')])],
        ],
        'Other ethnoracial identity connections is missing a required translation' => [
            'state' => [
                'other_ethnoracial_identity_connection' => ['es' => 'otra identidad'],
                'has_other_ethnoracial_identity_connection' => true,
                'has_ethnoracial_identity_connections' => true,
            ],
            'errors' => fn () => [
                'other_ethnoracial_identity_connection.en' => __('There is no ethnoracial identity filled in under "something else". Please fill this in.'),
                'other_ethnoracial_identity_connection.fr' => __('There is no ethnoracial identity filled in under "something else". Please fill this in.'),
            ],
            'without' => ['other_ethnoracial_identity_connection'],
        ],
        'Language connections is invalid' => [
            'state' => ['language_connections' => 123],
            'errors' => fn () => ['language_connections' => __('validation.array', ['attribute' => __('language connections')])],
        ],
        'Language connection is invalid' => [
            'state' => ['language_connections' => ['123']],
            'errors' => fn () => ['language_connections.0' => __('You must select a language.')],
        ],
        'Connection lived experience is missing' => [
            'state' => ['connection_lived_experience' => null],
            'errors' => fn () => ['connection_lived_experience' => __('You must select one option for “Do you have lived experience of the people you can connect to?”')],
        ],
        'Connection lived experience is not a string' => [
            'state' => ['connection_lived_experience' => 123],
            'errors' => fn () => ['connection_lived_experience' => __('validation.string', ['attribute' => __('connection lived experience')])],
        ],
        'Connection lived experience is invalid' => [
            'state' => ['connection_lived_experience' => 'other'],
            'errors' => fn () => ['connection_lived_experience' => __('validation.exists', ['attribute' => __('connection lived experience')])],
        ],
    ];
});
