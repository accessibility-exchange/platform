<?php

use App\Enums\BaseDisabilityType;
use App\Enums\OrganizationType;

dataset('updateOrganizationConstituenciesRequestValidationErrors', function () {
    $orgState = ['type' => OrganizationType::Representative->value];

    return [
        'Disability and deaf is not a boolean' => [
            'orgState' => $orgState,
            'state' => ['disability_and_deaf' => 123],
            'errors' => fn () => ['disability_and_deaf' => __('validation.boolean', ['attribute' => __('Disability and/or Deaf identity')])],
        ],
        'Lived experience constituencies is missing' => [
            'orgState' => $orgState,
            'state' => [
                'disability_and_deaf' => false,
                'lived_experience_constituencies' => null,
            ],
            'errors' => fn () => ['lived_experience_constituencies' => __('You must select at least one option for "Do you specifically :represent_or_serve_and_support people with disabilities, Deaf persons, and/or their supporters?"', ['represent_or_serve_and_support' => __('represent')])],
        ],
        'Lived experience constituencies is not an array' => [
            'orgState' => $orgState,
            'state' => [
                'disability_and_deaf' => true,
                'lived_experience_constituencies' => false,
            ],
            'errors' => fn () => ['lived_experience_constituencies' => __('validation.array', ['attribute' => __('lived experience constituencies')])],
        ],
        'Lived experience constituencies is invalid' => [
            'orgState' => $orgState,
            'state' => [
                'disability_and_deaf' => true,
                'lived_experience_constituencies' => [1000000],
            ],
            'errors' => fn () => ['lived_experience_constituencies.0' => __('validation.exists', ['attribute' => __('lived experience constituencies')])],
        ],
        'Base disability type is missing' => [
            'orgState' => $orgState,
            'state' => [
                'disability_and_deaf' => true,
                'base_disability_type' => null,
            ],
            'errors' => fn () => ['base_disability_type' => __('You must select one option for “Please select people with disabilities that you specifically :represent_or_serve_and_support”.', ['represent_or_serve_and_support' => __('represent')])],
        ],
        'Base disability type is invalid' => [
            'orgState' => $orgState,
            'state' => [
                'disability_and_deaf' => true,
                'base_disability_type' => 'other',
            ],
            'errors' => fn () => ['base_disability_type' => __('validation.exists', ['attribute' => __('disability type')])],
        ],
        'Disability and deaf constituencies is missing' => [
            'orgState' => $orgState,
            'state' => [
                'disability_and_deaf' => true,
                'disability_and_deaf_constituencies' => null,
                'base_disability_type' => BaseDisabilityType::SpecificDisabilities->value,
            ],
            'errors' => fn () => ['disability_and_deaf_constituencies' => __('You must select which specific disability and/or Deaf groups your organization :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => __('represents')])],
        ],
        'Disability and deaf constituencies is not an array' => [
            'orgState' => $orgState,
            'state' => [
                'disability_and_deaf' => true,
                'disability_and_deaf_constituencies' => true,
                'base_disability_type' => BaseDisabilityType::SpecificDisabilities->value,
            ],
            'errors' => fn () => ['disability_and_deaf_constituencies' => __('validation.array', ['attribute' => __('disability and deaf constituencies')])],
        ],
        'Disability and deaf constituencies is invalid' => [
            'orgState' => $orgState,
            'state' => [
                'disability_and_deaf' => true,
                'disability_and_deaf_constituencies' => [1000000],
                'base_disability_type' => BaseDisabilityType::SpecificDisabilities->value,
            ],
            'errors' => fn () => ['disability_and_deaf_constituencies.0' => __('validation.exists', ['attribute' => __('disability and deaf constituencies')])],
        ],
        'Has other disability constituency is not a boolean' => [
            'orgState' => $orgState,
            'state' => ['has_other_disability_constituency' => 123],
            'errors' => fn () => ['has_other_disability_constituency' => __('validation.boolean', ['attribute' => __('has other disability constituency')])],
        ],
        'Other disability constituency is missing' => [
            'orgState' => $orgState,
            'state' => [
                'other_disability_constituency' => null,
                'has_other_disability_constituency' => true,
                'base_disability_type' => BaseDisabilityType::SpecificDisabilities->value,
            ],
            'errors' => fn () => [
                'other_disability_constituency.en' => __('There is no disability type filled in under "something else". Please fill this in.'),
                'other_disability_constituency.fr' => __('There is no disability type filled in under "something else". Please fill this in.'),
            ],
        ],
        'Other disability constituency is missing required translation' => [
            'orgState' => $orgState,
            'state' => [
                'other_disability_constituency' => ['es' => 'otros distritos electorales de discapacidad'],
                'has_other_disability_constituency' => true,
                'base_disability_type' => BaseDisabilityType::SpecificDisabilities->value,
            ],
            'errors' => fn () => [
                'other_disability_constituency.en' => __('There is no disability type filled in under "something else". Please fill this in.'),
                'other_disability_constituency.fr' => __('There is no disability type filled in under "something else". Please fill this in.'),
            ],
        ],
        'Other disability constituency is not an array' => [
            'orgState' => $orgState,
            'state' => [
                'other_disability_constituency' => 123,
                'has_other_disability_constituency' => true,
                'base_disability_type' => BaseDisabilityType::SpecificDisabilities->value,
            ],
            'errors' => fn () => ['other_disability_constituency' => __('validation.array', ['attribute' => __('other disability constituency')])],
        ],
        'Other disability constituency is not a string' => [
            'orgState' => $orgState,
            'state' => [
                'other_disability_constituency' => ['en' => 123],
                'has_other_disability_constituency' => true,
                'base_disability_type' => BaseDisabilityType::SpecificDisabilities->value,
            ],
            'errors' => fn () => ['other_disability_constituency.en' => __('validation.string', ['attribute' => __('other disability constituency')])],
        ],
        'Has indigenous constituencies is missing' => [
            'orgState' => $orgState,
            'state' => ['has_indigenous_constituencies' => null],
            'errors' => fn () => ['has_indigenous_constituencies' => __('You must select one option for “Does your organization specifically :represent_or_serve_and_support people who are First Nations, Inuit, or Métis?”', ['represent_or_serve_and_support' => __('represent')])],
        ],
        'Has indigenous constituencies is not a boolean' => [
            'orgState' => $orgState,
            'state' => ['has_indigenous_constituencies' => 123],
            'errors' => fn () => ['has_indigenous_constituencies' => __('validation.boolean', ['attribute' => __('has indigenous constituencies')])],
        ],
        'Indigenous constituencies is missing' => [
            'orgState' => $orgState,
            'state' => [
                'has_indigenous_constituencies' => true,
                'indigenous_constituencies' => null,
            ],
            'errors' => fn () => ['indigenous_constituencies' => __('You must select at least one Indigenous group your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => __('represents')])],
        ],
        'Indigenous constituencies is not an array' => [
            'orgState' => $orgState,
            'state' => [
                'has_indigenous_constituencies' => true,
                'indigenous_constituencies' => false,
            ],
            'errors' => fn () => ['indigenous_constituencies' => __('validation.array', ['attribute' => __('indigenous constituencies')])],
        ],
        'Indigenous constituency is invalid' => [
            'orgState' => $orgState,
            'state' => [
                'has_indigenous_constituencies' => true,
                'indigenous_constituencies' => [1000000],
            ],
            'errors' => fn () => ['indigenous_constituencies.0' => __('validation.exists', ['attribute' => __('indigenous constituencies')])],
        ],
        'Refugees and immigrants is missing' => [
            'orgState' => $orgState,
            'state' => ['refugees_and_immigrants' => null],
            'errors' => fn () => ['refugees_and_immigrants' => __('You must select one option for “Does your organization specifically :represent_or_serve_and_support refugees and/or immigrants?”', ['represent_or_serve_and_support' => __('represent')])],
        ],
        'Refugees and immigrants is not a boolean' => [
            'orgState' => $orgState,
            'state' => ['refugees_and_immigrants' => 123],
            'errors' => fn () => ['refugees_and_immigrants' => __('validation.boolean', ['attribute' => __('Refugees and/or immigrants')])],
        ],
        'Has gender and sexuality consistencies is missing' => [
            'orgState' => $orgState,
            'state' => ['has_gender_and_sexuality_constituencies' => null],
            'errors' => fn () => ['has_gender_and_sexuality_constituencies' => __('You must select one option for “Does your organization specifically :represent_or_serve_and_support people who are marginalized based on gender or sexual identity?”', ['represent_or_serve_and_support' => __('represent')])],
        ],
        'Has gender and sexuality consistencies is not a boolean' => [
            'orgState' => $orgState,
            'state' => ['has_gender_and_sexuality_constituencies' => 123],
            'errors' => fn () => ['has_gender_and_sexuality_constituencies' => __('validation.boolean', ['attribute' => __('has gender and sexuality constituencies')])],
        ],
        'Gender and sexuality consistencies is missing' => [
            'orgState' => $orgState,
            'state' => [
                'gender_and_sexuality_constituencies' => null,
                'has_gender_and_sexuality_constituencies' => true,
                'nb_gnc_fluid_identity' => null,
            ],
            'errors' => fn () => [
                'gender_and_sexuality_constituencies' => __('You must select at least one gender or sexual identity group your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => __('represents')]),
                'nb_gnc_fluid_identity' => __('You must select at least one gender or sexual identity group your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => __('represents')]),
            ],
        ],
        'Gender and sexuality consistencies is not an array' => [
            'orgState' => $orgState,
            'state' => [
                'gender_and_sexuality_constituencies' => 123,
                'has_gender_and_sexuality_constituencies' => true,
                'nb_gnc_fluid_identity' => null,
            ],
            'errors' => fn () => ['gender_and_sexuality_constituencies' => __('validation.array', ['attribute' => __('gender and sexuality constituencies')])],
        ],
        'Gender and sexuality consistencies is invalid' => [
            'orgState' => $orgState,
            'state' => [
                'gender_and_sexuality_constituencies' => [100000],
                'has_gender_and_sexuality_constituencies' => true,
                'nb_gnc_fluid_identity' => null,
            ],
            'errors' => fn () => ['gender_and_sexuality_constituencies.0' => __('validation.exists', ['attribute' => __('gender and sexuality constituencies')])],
        ],
        'Non-binary gender non-conforming fluid identity is missing' => [
            'orgState' => $orgState,
            'state' => [
                'gender_and_sexuality_constituencies' => [],
                'has_gender_and_sexuality_constituencies' => true,
                'nb_gnc_fluid_identity' => null,
            ],
            'errors' => fn () => [
                'gender_and_sexuality_constituencies' => __('You must select at least one gender or sexual identity group your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => __('represents')]),
                'nb_gnc_fluid_identity' => __('You must select at least one gender or sexual identity group your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => __('represents')]),
            ],
        ],
        'Non-binary gender non-conforming fluid identity is not a boolean' => [
            'orgState' => $orgState,
            'state' => [
                'gender_and_sexuality_constituencies' => [],
                'has_gender_and_sexuality_constituencies' => true,
                'nb_gnc_fluid_identity' => 123,
            ],
            'errors' => fn () => ['nb_gnc_fluid_identity' => __('validation.boolean', ['attribute' => __('Non-binary/Gender non-conforming/Gender fluid identity')])],
        ],
        'Has age bracket constituencies is missing' => [
            'orgState' => $orgState,
            'state' => ['has_age_bracket_constituencies' => null],
            'errors' => fn () => ['has_age_bracket_constituencies' => __('You must select one option for “Does your organization :represent_or_serve_and_support a specific age bracket or brackets?”', ['represent_or_serve_and_support' => __('represent')])],
        ],
        'Has age bracket constituencies is not a boolean' => [
            'orgState' => $orgState,
            'state' => ['has_age_bracket_constituencies' => 123],
            'errors' => fn () => ['has_age_bracket_constituencies' => __('validation.boolean', ['attribute' => __('has age bracket constituencies')])],
        ],
        'Age bracket constituencies is missing' => [
            'orgState' => $orgState,
            'state' => [
                'has_age_bracket_constituencies' => true,
                'age_bracket_constituencies' => null,
            ],
            'errors' => fn () => ['age_bracket_constituencies' => __('You must select at least one age group your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => __('represents')])],
        ],
        'Age bracket constituencies is not an array' => [
            'orgState' => $orgState,
            'state' => [
                'has_age_bracket_constituencies' => true,
                'age_bracket_constituencies' => false,
            ],
            'errors' => fn () => ['age_bracket_constituencies' => __('validation.array', ['attribute' => __('age bracket constituencies')])],
        ],
        'Age bracket constituency is invalid' => [
            'orgState' => $orgState,
            'state' => [
                'has_age_bracket_constituencies' => true,
                'age_bracket_constituencies' => [100000],
            ],
            'errors' => fn () => ['age_bracket_constituencies.0' => __('validation.exists', ['attribute' => __('age bracket constituencies')])],
        ],
        'Has ethnoracial identity constituencies is missing' => [
            'orgState' => $orgState,
            'state' => ['has_ethnoracial_identity_constituencies' => null],
            'errors' => fn () => ['has_ethnoracial_identity_constituencies' => __('You must select one option for “Does your organization :represent_or_serve_and_support a specific ethnoracial identity or identities?”', ['represent_or_serve_and_support' => __('represent')])],
        ],
        'Has ethnoracial identity constituencies is not a boolean' => [
            'orgState' => $orgState,
            'state' => ['has_ethnoracial_identity_constituencies' => 123],
            'errors' => fn () => ['has_ethnoracial_identity_constituencies' => __('validation.boolean', ['attribute' => __('has ethnoracial identity constituencies')])],
        ],
        'Ethnoracial identity constituencies is missing' => [
            'orgState' => $orgState,
            'state' => [
                'ethnoracial_identity_constituencies' => null,
                'has_ethnoracial_identity_constituencies' => true,
                'has_other_ethnoracial_identity_constituency' => null,
            ],
            'errors' => fn () => ['ethnoracial_identity_constituencies' => __('You must select at least one ethno-racial identity your organization specifically :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => __('represents')])],
        ],
        'Ethnoracial identity constituencies is not an array' => [
            'orgState' => $orgState,
            'state' => [
                'ethnoracial_identity_constituencies' => true,
                'has_ethnoracial_identity_constituencies' => true,
                'has_other_ethnoracial_identity_constituency' => null,
            ],
            'errors' => fn () => ['ethnoracial_identity_constituencies' => __('validation.array', ['attribute' => __('ethnoracial identity constituencies')])],
        ],
        'Ethnoracial identity constituency is invalid' => [
            'orgState' => $orgState,
            'state' => [
                'ethnoracial_identity_constituencies' => [1000000],
                'has_ethnoracial_identity_constituencies' => true,
                'has_other_ethnoracial_identity_constituency' => null,
            ],
            'errors' => fn () => ['ethnoracial_identity_constituencies.0' => __('validation.exists', ['attribute' => __('ethnoracial identity constituencies')])],
        ],
        'Has other ethnoracial identity constituencies is not a boolean' => [
            'orgState' => $orgState,
            'state' => ['has_other_ethnoracial_identity_constituency' => 123],
            'errors' => fn () => ['has_other_ethnoracial_identity_constituency' => __('validation.boolean', ['attribute' => __('has other ethnoracial identity constituency')])],
        ],
        'Other ethnoracial identity constituencies is not an array' => [
            'orgState' => $orgState,
            'state' => [
                'other_ethnoracial_identity_constituency' => 123,
                'has_other_ethnoracial_identity_constituency' => true,
                'has_ethnoracial_identity_constituencies' => true,
            ],
            'errors' => fn () => ['other_ethnoracial_identity_constituency' => __('validation.array', ['attribute' => __('other ethnoracial identity constituency')])],
        ],
        'Other ethnoracial identity constituency is not a string' => [
            'orgState' => $orgState,
            'state' => [
                'other_ethnoracial_identity_constituency' => ['en' => false],
                'has_other_ethnoracial_identity_constituency' => true,
                'has_ethnoracial_identity_constituencies' => true,
            ],
            'errors' => fn () => ['other_ethnoracial_identity_constituency.en' => __('validation.string', ['attribute' => __('other ethnoracial identity constituency')])],
        ],
        'Other ethnoracial identity constituencies is missing a required translation' => [
            'orgState' => $orgState,
            'state' => [
                'other_ethnoracial_identity_constituency' => ['es' => 'otra identidad'],
                'has_other_ethnoracial_identity_constituency' => true,
                'has_ethnoracial_identity_constituencies' => true,
            ],
            'errors' => fn () => [
                'other_ethnoracial_identity_constituency.en' => __('There is no ethnoracial identity filled in under "something else". Please fill this in.'),
                'other_ethnoracial_identity_constituency.fr' => __('There is no ethnoracial identity filled in under "something else". Please fill this in.'),
            ],
        ],
        'Language constituencies is invalid' => [
            'orgState' => $orgState,
            'state' => ['language_constituencies' => ['123']],
            'errors' => fn () => ['language_constituencies.0' => __('You must select a language.')],
        ],
        'Area type constituencies is missing' => [
            'orgState' => $orgState,
            'state' => ['area_type_constituencies' => null],
            'errors' => fn () => ['area_type_constituencies' => __('You must select at least one option for “Where do the people that you :represent_or_serve_and_support come from?”', ['represent_or_serve_and_support' => __('represent')])],
        ],
        'Area type constituencies is not an array' => [
            'orgState' => $orgState,
            'state' => ['area_type_constituencies' => 123],
            'errors' => fn () => ['area_type_constituencies' => __('validation.array', ['attribute' => __('area type constituencies')])],
        ],
        'Area type constituencies is invalid' => [
            'orgState' => $orgState,
            'state' => ['area_type_constituencies' => [1000000]],
            'errors' => fn () => ['area_type_constituencies.0' => __('validation.exists', ['attribute' => __('area type constituencies')])],
        ],
        'Staff lived experience is missing' => [
            'orgState' => $orgState,
            'state' => ['staff_lived_experience' => null],
            'errors' => fn () => ['staff_lived_experience' => __('You must select one option for “Do you have staff who have lived experience of the people you :represent_or_serve_and_support?”', ['represent_or_serve_and_support' => __('represent')])],
        ],
        'Staff lived experience is not a string' => [
            'orgState' => $orgState,
            'state' => ['staff_lived_experience' => 123],
            'errors' => fn () => ['staff_lived_experience' => __('validation.string', ['attribute' => __('Staff lived experience')])],
        ],
        'Staff lived experience is invalid' => [
            'orgState' => $orgState,
            'state' => ['staff_lived_experience' => 'other'],
            'errors' => fn () => ['staff_lived_experience' => __('validation.exists', ['attribute' => __('Staff lived experience')])],
        ],
    ];
});
