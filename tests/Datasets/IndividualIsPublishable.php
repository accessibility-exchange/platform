<?php

use App\Enums\CommunityConnectorHasLivedExperience;
use App\Enums\ConsultingService;
use App\Enums\IndividualRole;
use App\Enums\MeetingType;

dataset('individualIsPublishable', function () {
    $baseModel = [
        'bio' => 'test bio',
        'connection_lived_experience' => CommunityConnectorHasLivedExperience::YesAll->value,
        'consulting_services' => [ConsultingService::Analysis->value],
        'extra_attributes' => [
            'has_age_brackets' => 0,
            'has_ethnoracial_identities' => 0,
            'has_gender_and_sexual_identities' => 0,
            'has_indigenous_identities' => 0,
        ],
        'meeting_types' => [MeetingType::InPerson->value],
        'roles' => [IndividualRole::AccessibilityConsultant->value],
    ];

    return [
        'not publishable when missing bio' => [
            false,
            array_replace_recursive($baseModel, ['bio' => null]),
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing connection_lived_experience' => [
            false,
            array_replace_recursive($baseModel, ['connection_lived_experience' => null]),
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing consulting_services' => [
            false,
            array_replace_recursive($baseModel, ['consulting_services' => null]),
            [
                'areaTypeConnections',
                'livedExperienceConnections',
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
                'areaTypeConnections',
                'livedExperienceConnections',
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
                'areaTypeConnections',
                'livedExperienceConnections',
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
                'areaTypeConnections',
                'livedExperienceConnections',
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
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing meeting_types' => [
            false,
            array_replace_recursive($baseModel, ['meeting_types' => null]),
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing name' => [
            false,
            array_replace_recursive($baseModel, ['name' => null]),
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing region' => [
            false,
            array_replace_recursive($baseModel, ['region' => null]),
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing roles' => [
            false,
            array_replace_recursive($baseModel, ['roles' => null]),
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing ageBracketConnections' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_age_brackets' => 1,
                ],
            ]),
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing indigenousIdentityConnections' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_indigenous_identities' => 1,
                ],
            ]),
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing areaTypeConnections' => [
            false,
            $baseModel,

            [
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing livedExperienceConnections' => [
            false,
            $baseModel,
            [
                'areaTypeConnections',
            ],
        ],
        'not publishable using participant role' => [
            false,
            array_replace_recursive($baseModel, ['roles' => [IndividualRole::ConsultationParticipant->value]]),
            [
                'areaTypeConnections',
                'livedExperienceConnections',
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
                'ageBracketConnections',
                'areaTypeConnections',
                'indigenousIdentityConnections',
                'livedExperienceConnections',
            ],
        ],
        'publishable without optional connections' => [
            true,
            $baseModel,
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'publishable using connector role' => [
            true,
            array_replace_recursive($baseModel, ['roles' => [IndividualRole::CommunityConnector->value]]),
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
    ];
});
