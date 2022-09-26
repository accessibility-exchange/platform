<?php

use App\Enums\ConsultingService;
use App\Enums\OrganizationRole;
use App\Enums\ProvinceOrTerritory;

dataset('organizationIsPublishable', function () {
    $baseModel = [
        'about' => 'test organization about',
        'consulting_services' => [ConsultingService::Analysis->value],
        'contact_person_name' => 'contact name',
        'contact_person_phone' => '4165555555',
        'extra_attributes' => [
            'has_age_brackets' => 0,
            'has_ethnoracial_identities' => 0,
            'has_gender_and_sexual_identities' => 0,
            'has_refugee_and_immigrant_constituency' => 0,
            'has_indigenous_identities' => 0,
        ],
        'locality' => 'Toronto',
        'preferred_contact_method' => 'email',
        'region' => 'ON',
        'roles' => [OrganizationRole::AccessibilityConsultant],
        'service_areas' => [ProvinceOrTerritory::Ontario->value],
        'staff_lived_experience' => 'yes',
    ];

    return [
        'not publishable when missing about' => [
            false,
            array_replace_recursive($baseModel, ['about' => null]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing consulting_services as a consultant' => [
            false,
            array_replace_recursive($baseModel, ['consulting_services' => null]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing contact_person_email' => [
            false,
            array_replace_recursive($baseModel, [
                'contact_person_email' => null,
                'contact_person_phone' => null,
            ]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing contact_person_name' => [
            false,
            array_replace_recursive($baseModel, ['contact_person_name' => null]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing contact_person_phone' => [
            false,
            array_replace_recursive($baseModel, [
                'contact_person_phone' => null,
                'contact_person_vrs' => true,
            ]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing has_age_brackets' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_age_brackets' => null,
                ],
            ]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing has_ethnoracial_identities' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_ethnoracial_identities' => null,
                ],
            ]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing has_gender_and_sexual_identities' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_gender_and_sexual_identities' => null,
                ],
            ]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing has_refugee_and_immigrant_constituency' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_refugee_and_immigrant_constituency' => null,
                ],
            ]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing has_indigenous_identities' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_indigenous_identities' => null,
                ],
            ]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing languages' => [
            false,
            array_replace_recursive($baseModel, ['languages' => null]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing locality' => [
            false,
            array_replace_recursive($baseModel, ['locality' => null]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing name' => [
            false,
            array_replace_recursive($baseModel, ['name' => null]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing preferred_contact_method' => [
            false,
            array_replace_recursive($baseModel, ['preferred_contact_method' => null]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing region' => [
            false,
            array_replace_recursive($baseModel, ['region' => null]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing roles' => [
            false,
            array_replace_recursive($baseModel, ['roles' => null]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing service_areas' => [
            false,
            array_replace_recursive($baseModel, ['service_areas' => null]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing staff_lived_experiences' => [
            false,
            array_replace_recursive($baseModel, ['staff_lived_experience' => null]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing type' => [
            false,
            array_replace_recursive($baseModel, ['type' => null]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing working_languages' => [
            false,
            array_replace_recursive($baseModel, ['working_languages' => null]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing ageBrackets' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_age_brackets' => 1,
                ],
            ]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing indigenousIdentities' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_indigenous_identities' => 1,
                ],
            ]),
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
        'not publishable when missing areaTypes' => [
            false,
            $baseModel,
            [
                'livedExperiences',
            ],
        ],
        'not publishable when missing livedExperiences' => [
            false,
            $baseModel,
            [
                'areaTypes',
            ],
        ],
        'publishable with all expected values' => [
            true,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_age_brackets' => 1,
                    'has_indigenous_identities' => 1,
                ],
            ]),
            [
                'ageBrackets',
                'areaTypes',
                'indigenousIdentities',
                'livedExperiences',
            ],
        ],
        'publishable without optional values' => [
            true,
            $baseModel,
            [
                'areaTypes',
                'livedExperiences',
            ],
        ],
    ];
});
