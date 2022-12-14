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
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing consulting_services as a consultant' => [
            false,
            array_replace_recursive($baseModel, ['consulting_services' => null]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing contact_person_email' => [
            false,
            array_replace_recursive($baseModel, [
                'contact_person_email' => null,
                'contact_person_phone' => null,
            ]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing contact_person_name' => [
            false,
            array_replace_recursive($baseModel, ['contact_person_name' => null]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing contact_person_phone' => [
            false,
            array_replace_recursive($baseModel, [
                'contact_person_phone' => null,
                'contact_person_vrs' => true,
            ]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing languages' => [
            false,
            array_replace_recursive($baseModel, ['languages' => null]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing locality' => [
            false,
            array_replace_recursive($baseModel, ['locality' => null]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing name' => [
            false,
            array_replace_recursive($baseModel, ['name' => null]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing preferred_contact_method' => [
            false,
            array_replace_recursive($baseModel, ['preferred_contact_method' => null]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing region' => [
            false,
            array_replace_recursive($baseModel, ['region' => null]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing roles' => [
            false,
            array_replace_recursive($baseModel, ['roles' => null]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing service_areas' => [
            false,
            array_replace_recursive($baseModel, ['service_areas' => null]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing staff_lived_experiences' => [
            false,
            array_replace_recursive($baseModel, ['staff_lived_experience' => null]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing type' => [
            false,
            array_replace_recursive($baseModel, ['type' => null]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing working_languages' => [
            false,
            array_replace_recursive($baseModel, ['working_languages' => null]),
            [
                'areaTypeConstituencies',
            ],
        ],
        'not publishable when missing areaTypes' => [
            false,
            $baseModel,
            [
            ],
        ],
        'not publishable when pending approval' => [
            false,
            array_replace_recursive($baseModel, [
                'oriented_at' => null,
                'validated_at' => null,
            ]),
            [
                'areaTypeConstituencies',

            ],
        ],
        'publishable with all expected values' => [
            true,
            $baseModel,
            [
                'ageBracketConstituencies',
                'areaTypeConstituencies',
                'ethnoracialIdentityConstituencies',
                'genderAndSexualityConstituencies',
                'indigenousConstituencies',
            ],
        ],
        'publishable with gender identities' => [
            true,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_gender_and_sexual_identities' => 1,
                ],
            ]),
            [
                'areaTypeConstituencies',
                'genderAndSexualityConstituencies',
            ],
        ],
        'publishable without optional values' => [
            true,
            $baseModel,
            [
                'areaTypeConstituencies',
            ],
        ],
    ];
});
