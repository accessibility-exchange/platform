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
            fn () => ['disability_types' => __('If you are looking for a specific :attribute, you must select at least one.', ['attribute' => __('Disability or Deaf group')])],
        ],
        'Disability types is not an array' => [
            [
                'cross_disability_and_deaf' => false,
                'disability_types' => 1,
            ],
            fn () => ['disability_types' => __('validation.array', ['attribute' => __('Disability or Deaf group')])],
        ],
        'Disability types is invalid' => [
            [
                'cross_disability_and_deaf' => false,
                'disability_types' => [1000],
            ],
            fn () => ['disability_types.0' => __('You must select a valid :attribute.', ['attribute' => __('Disability or Deaf group')])],
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
            fn () => ['age_brackets' => __('If you are interested in engaging a specific :attribute, you must select at least one.', ['attribute' => __('age group')])],
            ['age_brackets'],
        ],
        'Age brackets is not an array' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::AgeBracket->value,
                'age_brackets' => 1,
            ],
            fn () => ['age_brackets' => __('validation.array', ['attribute' => __('age group')])],
        ],
        'Age bracket is invalid' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::AgeBracket->value,
                'age_brackets' => [1000],
            ],
            fn () => ['age_brackets.0' => __('You must select a valid :attribute.', ['attribute' => __('age group')])],
        ],
        'Gender and sexual identities missing' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::GenderAndSexualIdentity->value,
            ],
            fn () => ['gender_and_sexual_identities' => __('If you are interested in engaging a specific :attribute, you must select at least one.', ['attribute' => __('gender or sexual identity group')])],
            ['gender_and_sexual_identities'],
        ],
        'Gender and sexual identities is not an array' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::GenderAndSexualIdentity->value,
                'gender_and_sexual_identities' => 1,
            ],
            fn () => ['gender_and_sexual_identities' => __('validation.array', ['attribute' => __('gender or sexual identity group')])],
        ],
        'Gender and sexual identity is invalid' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::GenderAndSexualIdentity->value,
                'gender_and_sexual_identities' => [1000],
            ],
            fn () => ['gender_and_sexual_identities.0' => __('You must select a valid :attribute.', ['attribute' => __('gender or sexual identity group')])],
        ],
        'Non-binary/Gender non-conforming/Fluid identity missing' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::GenderAndSexualIdentity->value,
                'gender-and-sexual-identity' => [],
            ],
            fn () => ['nb_gnc_fluid_identity' => __('If you are interested in engaging a specific :attribute, you must select at least one.', ['attribute' => __('gender or sexual identity group')])],
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
            fn () => ['indigenous_identities' => __('If you are interested in engaging a specific :attribute, you must select at least one.', ['attribute' => __('indigenous group')])],
            ['indigenous_identities'],
        ],
        'Indigenous identities is not an array' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::IndigenousIdentity->value,
                'indigenous_identities' => 1,
            ],
            fn () => ['indigenous_identities' => __('validation.array', ['attribute' => __('indigenous group')])],
        ],
        'Indigenous identity is invalid' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::IndigenousIdentity->value,
                'indigenous_identities' => [1000],
            ],
            fn () => ['indigenous_identities.0' => __('You must select a valid :attribute.', ['attribute' => __('indigenous group')])],
        ],
        'Ethnoracial identities missing' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::EthnoracialIdentity->value,
            ],
            fn () => ['ethnoracial_identities' => __('If you are interested in engaging a specific :attribute, you must select at least one.', ['attribute' => __('ethnoracial group')])],
            ['ethnoracial_identities'],
        ],
        'Ethnoracial identities is not an array' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::EthnoracialIdentity->value,
                'ethnoracial_identities' => 1,
            ],
            fn () => ['ethnoracial_identities' => __('validation.array', ['attribute' => __('ethnoracial group')])],
        ],
        'Ethnoracial identity is invalid' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::EthnoracialIdentity->value,
                'ethnoracial_identities' => [1000],
            ],
            fn () => ['ethnoracial_identities.0' => __('You must select a valid :attribute.', ['attribute' => __('ethnoracial group')])],
        ],
        'First languages missing' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::FirstLanguage->value,
            ],
            fn () => ['first_languages' => __('If you are interested in engaging a specific :attribute, you must select at least one.', ['attribute' => __('first language')])],
            ['first_languages'],
        ],
        'First languages is not an array' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::FirstLanguage->value,
                'first_languages' => 1,
            ],
            fn () => ['first_languages' => __('validation.array', ['attribute' => __('first language')])],
        ],
        'First language is invalid' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::FirstLanguage->value,
                'first_languages' => ['XX'],
            ],
            fn () => ['first_languages.0' => __('You must select a valid :attribute.', ['attribute' => __('first language')])],
        ],
        'Area types missing' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::AreaType->value,
            ],
            fn () => ['area_types' => __('If you are interested in engaging a specific :attribute, you must select at least one.', ['attribute' => __('area type')])],
            ['area_types'],
        ],
        'Area types is not an array' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::AreaType->value,
                'area_types' => 1,
            ],
            fn () => ['area_types' => __('validation.array', ['attribute' => __('area type')])],
        ],
        'Area type is invalid' => [
            [
                'intersectional' => false,
                'other_identity_type' => IdentityType::AreaType->value,
                'area_types' => [1000],
            ],
            fn () => ['area_types.0' => __('You must select a valid :attribute.', ['attribute' => __('area type')])],
        ],
        'Ideal participants is missing' => [
            ['ideal_participants' => null],
            fn () => ['ideal_participants' => __('validation.required', ['attribute' => __('ideal number of participants')])],
            ['ideal_participants'],
        ],
        'Ideal participants is not an integer' => [
            ['ideal_participants' => 'ten'],
            fn () => ['ideal_participants' => __('validation.integer', ['attribute' => __('ideal number of participants')])],
        ],
        'Ideal participants is below minimum' => [
            [
                'minimum_participants' => 5,
                'ideal_participants' => 8,
            ],
            fn () => ['ideal_participants' => __('validation.min.numeric', ['attribute' => __('ideal number of participants'), 'min' => 10])],
        ],
        'Minimum participants is missing' => [
            ['minimum_participants' => null],
            fn () => ['minimum_participants' => __('You must enter a :attribute.', ['attribute' => __('minimum number of participants')])],
            ['minimum_participants'],
        ],
        'Minimum participants is not an integer' => [
            ['minimum_participants' => 'ten'],
            fn () => ['minimum_participants' => __('The :attribute must be a number.', ['attribute' => __('minimum number of participants')])],
        ],
        'Minimum participants is below minimum' => [
            [
                'minimum_participants' => 8,
            ],
            fn () => ['minimum_participants' => __('validation.min.numeric', ['attribute' => __('minimum number of participants'), 'min' => 10])],
        ],
        'Minimum participants is more than ideal participants' => [
            [
                'minimum_participants' => 15,
                'ideal_participants' => 12,
            ],
            fn () => ['minimum_participants' => __('Please enter a :attribute that is less than or the same as the ideal number of participants.', ['attribute' => __('minimum number of participants')])],
        ],
    ];
});
