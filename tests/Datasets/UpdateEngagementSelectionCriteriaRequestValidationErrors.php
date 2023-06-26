<?php

use App\Enums\IdentityType;
use App\Enums\LocationType;
use App\Enums\ProvinceOrTerritory;

dataset('updateEngagementSelectionCriteriaRequestValidationErrors', function () {
    return [
        'Location type missing' => [
            ['location_type' => null],
            fn () => ['location_type' => __('validation.required', ['attribute' => 'location type'])],
        ],
        'Location type is invalid' => [
            ['location_type' => 'test location'],
            fn () => ['location_type' => __('validation.in', ['attribute' => 'location type'])],
        ],
        'Regions is missing' => [
            fn () => ['location_type' => LocationType::Regions->value],
            fn () => ['regions' => __('You must choose at least one province or territory.')],
            ['regions'],
        ],
        'Regions is not an array' => [
            fn () => [
                'location_type' => LocationType::Regions->value,
                'regions' => ProvinceOrTerritory::Ontario->value,
            ],
            fn () => ['regions' => __('validation.array', ['attribute' => 'regions'])],
        ],
        'Region is invalid' => [
            fn () => [
                'location_type' => LocationType::Regions->value,
                'regions' => ['XX'],
            ],
            fn () => ['regions.0' => __('You must choose a valid province or territory')],
        ],
        'Locations missing' => [
            fn () => ['location_type' => LocationType::Localities->value],
            fn () => ['locations' => __('You must enter at least one city or town.')],
            ['locations'],
        ],
        'Locations is not an array' => [
            fn () => [
                'location_type' => LocationType::Localities->value,
                'locations' => 'my location',
            ],
            fn () => ['locations' => __('validation.array', ['attribute' => 'locations'])],
        ],
        'Location region is missing' => [
            fn () => [
                'location_type' => LocationType::Localities->value,
                'locations' => [['locality' => 'my city']],
            ],
            fn () => ['locations.0.region' => __('You must enter a province or territory.')],
        ],
        'Location region is invalid' => [
            fn () => [
                'location_type' => LocationType::Localities->value,
                'locations' => [
                    [
                        'region' => 'XX',
                        'locality' => 'my city',
                    ],
                ],
            ],
            fn () => ['locations.0.region' => __('You must enter a province or territory.')],
        ],
        'Location locality is missing' => [
            fn () => [
                'location_type' => LocationType::Localities->value,
                'locations' => [['region' => ProvinceOrTerritory::Ontario->value]],
            ],
            fn () => ['locations.0.locality' => __('You must enter a city or town.')],
        ],
        'Location locality is invalid' => [
            fn () => [
                'location_type' => LocationType::Localities->value,
                'locations' => [
                    [
                        'region' => ProvinceOrTerritory::Ontario->value,
                        'locality' => false,
                    ],
                ],
            ],
            fn () => ['locations.0.locality' => __('You must enter a city or town.')],
        ],
        'Cross disability and deaf missing' => [
            ['cross_disability_and_deaf' => null],
            fn () => ['cross_disability_and_deaf' => __('validation.required', ['attribute' => 'cross disability and deaf'])],
        ],
        'Cross disability and deaf is not boolean' => [
            ['cross_disability_and_deaf' => 'true'],
            fn () => ['cross_disability_and_deaf' => __('validation.boolean', ['attribute' => 'cross disability and deaf'])],
        ],
        'Disability types missing' => [
            [
                'cross_disability_and_deaf' => false,
            ],
            fn () => ['disability_types' => __('One or more Disability or Deaf groups are required.')],
        ],
        'Disability types is not an array' => [
            [
                'cross_disability_and_deaf' => false,
                'disability_types' => 1,
            ],
            fn () => ['disability_types' => __('validation.array', ['attribute' => 'disability types'])],
        ],
        'Disability types is invalid' => [
            [
                'cross_disability_and_deaf' => false,
                'disability_types' => [1000],
            ],
            fn () => ['disability_types.0' => __('You must select a valid Disability or Deaf group.')],
        ],
        'Intersectional missing' => [
            ['intersectional' => null],
            fn () => ['intersectional' => __('validation.required', ['attribute' => 'intersectional'])],
        ],
        'Intersectional is not boolean' => [
            ['intersectional' => 'false'],
            fn () => ['intersectional' => __('validation.boolean', ['attribute' => 'intersectional'])],
        ],
        'Other identity type missing' => [
            ['intersectional' => false],
            fn () => ['other_identity_type' => __('If you are looking for a group with a specific experience or identity, you must select which type of experience or identity you are looking for.')],
            ['other_identity_type'],
        ],
        'Other identity type is not a string' => [
            [
                'intersectional' => false,
                'other_identity_type' => 1234,
            ],
            fn () => ['other_identity_type' => __('validation.string', ['attribute' => 'other identity type'])],
        ],
        'Age brackets missing' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::AgeBracket->value,
            ],
            fn () => ['age_brackets' => __('validation.required_if', ['attribute' => 'age brackets', 'other' => 'other identity type', 'value' => IdentityType::AgeBracket->value])],
            ['age_brackets'],
        ],
        'Age brackets is not an array' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::AgeBracket->value,
                'age_brackets' => 1,
            ],
            fn () => ['age_brackets' => __('validation.array', ['attribute' => 'age brackets'])],
        ],
        'Age bracket is invalid' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::AgeBracket->value,
                'age_brackets' => [1000],
            ],
            fn () => ['age_brackets.0' => __('You must select a valid age bracket.')],
        ],
        'Gender and sexual identities missing' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::GenderAndSexualIdentity->value,
            ],
            fn () => ['gender_and_sexual_identities' => __('You must select at least one gender or sexual identity group.')],
            ['gender_and_sexual_identities'],
        ],
        'Gender and sexual identities is not an array' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::GenderAndSexualIdentity->value,
                'gender_and_sexual_identities' => 1,
            ],
            fn () => ['gender_and_sexual_identities' => __('validation.array', ['attribute' => 'gender and sexual identities'])],
        ],
        'Gender and sexual identity is invalid' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::GenderAndSexualIdentity->value,
                'gender_and_sexual_identities' => [1000],
            ],
            fn () => ['gender_and_sexual_identities.0' => __('You must select a valid gender or sexual identity.')],
        ],
        'Non-binary/Gender non-conforming/Fluid identity missing' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::GenderAndSexualIdentity->value,
                'gender-and-sexual-identity' => [],
            ],
            fn () => ['nb_gnc_fluid_identity' => __('You must select at least one gender or sexual identity group.')],
            ['nb_gnc_fluid_identity'],
        ],
        'Non-binary/Gender non-conforming/Fluid identity is not boolean' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::GenderAndSexualIdentity->value,
                'gender-and-sexual-identity' => [],
                'nb_gnc_fluid_identity' => 'false',
            ],
            fn () => ['nb_gnc_fluid_identity' => __('validation.boolean', ['attribute' => __('Non-binary/Gender non-conforming/Gender fluid identity')])],
        ],
        'Indigenous identities missing' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::IndigenousIdentity->value,
            ],
            fn () => ['indigenous_identities' => __('validation.required_if', ['attribute' => 'indigenous identities', 'other' => 'other identity type', 'value' => IdentityType::IndigenousIdentity->value])],
            ['indigenous_identities'],
        ],
        'Indigenous identities is not an array' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::IndigenousIdentity->value,
                'indigenous_identities' => 1,
            ],
            fn () => ['indigenous_identities' => __('validation.array', ['attribute' => 'indigenous identities'])],
        ],
        'Indigenous identity is invalid' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::IndigenousIdentity->value,
                'indigenous_identities' => [1000],
            ],
            fn () => ['indigenous_identities.0' => __('You must select a valid indigenous identity.')],
        ],
        'Ethnoracial identities missing' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::EthnoracialIdentity->value,
            ],
            fn () => ['ethnoracial_identities' => __('validation.required_if', ['attribute' => 'ethnoracial identities', 'other' => 'other identity type', 'value' => IdentityType::EthnoracialIdentity->value])],
            ['ethnoracial_identities'],
        ],
        'Ethnoracial identities is not an array' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::EthnoracialIdentity->value,
                'ethnoracial_identities' => 1,
            ],
            fn () => ['ethnoracial_identities' => __('validation.array', ['attribute' => 'ethnoracial identities'])],
        ],
        'Ethnoracial identity is invalid' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::EthnoracialIdentity->value,
                'ethnoracial_identities' => [1000],
            ],
            fn () => ['ethnoracial_identities.0' => __('You must select a valid ethnoracial identity.')],
        ],
        'First languages missing' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::FirstLanguage->value,
            ],
            fn () => ['first_languages' => __('validation.required_if', ['attribute' => 'first languages', 'other' => 'other identity type', 'value' => IdentityType::FirstLanguage->value])],
            ['first_languages'],
        ],
        'First languages is not an array' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::FirstLanguage->value,
                'first_languages' => 1,
            ],
            fn () => ['first_languages' => __('validation.array', ['attribute' => 'first languages'])],
        ],
        'First language is invalid' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::FirstLanguage->value,
                'first_languages' => ['XX'],
            ],
            fn () => ['first_languages.0' => __('You must select a valid first language.')],
        ],
        'Area types missing' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::AreaType->value,
            ],
            fn () => ['area_types' => __('validation.required_if', ['attribute' => 'area types', 'other' => 'other identity type', 'value' => IdentityType::AreaType->value])],
            ['area_types'],
        ],
        'Area types is not an array' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::AreaType->value,
                'area_types' => 1,
            ],
            fn () => ['area_types' => __('validation.array', ['attribute' => 'area types'])],
        ],
        'Area type is invalid' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::AreaType->value,
                'area_types' => [1000],
            ],
            fn () => ['area_types.0' => __('You must select a valid area type.')],
        ],
        'Ideal participants is missing' => [
            ['ideal_participants' => null],
            fn () => ['ideal_participants' => __('validation.required', ['attribute' => 'ideal participants'])],
            ['ideal_participants'],
        ],
        'Ideal participants is not an integer' => [
            ['ideal_participants' => 'ten'],
            fn () => ['ideal_participants' => __('validation.integer', ['attribute' => 'ideal participants'])],
        ],
        'Ideal participants is below minimum' => [
            [
                'minimum_participants' => 5,
                'ideal_participants' => 8,
            ],
            fn () => ['ideal_participants' => __('validation.min.numeric', ['attribute' => 'ideal participants', 'min' => 10])],
        ],
        'Minimum participants is missing' => [
            ['minimum_participants' => null],
            fn () => ['minimum_participants' => __('validation.required', ['attribute' => 'minimum participants'])],
            ['minimum_participants'],
        ],
        'Minimum participants is not an integer' => [
            ['minimum_participants' => 'ten'],
            fn () => ['minimum_participants' => __('validation.integer', ['attribute' => 'minimum participants'])],
        ],
        'Minimum participants is below minimum' => [
            [
                'minimum_participants' => 8,
            ],
            fn () => ['minimum_participants' => __('validation.min.numeric', ['attribute' => 'minimum participants', 'min' => 10])],
        ],
        'Minimum participants is more than ideal participants' => [
            [
                'minimum_participants' => 15,
                'ideal_participants' => 12,
            ],
            fn () => ['minimum_participants' => __('The minimum number of participants is more than the ideal number of participants. Please enter a minimum that is less than or the same as the ideal number of participants.')],
        ],
    ];
});
