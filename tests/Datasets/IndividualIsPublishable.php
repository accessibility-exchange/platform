<?php

use App\Enums\CommunityConnectorHasLivedExperience;
use App\Enums\ConsultingService;
use App\Enums\IndividualRole;
use App\Enums\MeetingType;

dataset('individualIsPublishable', function () {
    $baseUser = [
        'oriented_at' => now(),
    ];

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
        'region' => 'NS',
    ];

    $baseConnections = [
        'areaTypeConnections',
        'livedExperienceConnections',
    ];

    return [
        'not publishable when missing bio' => [
            false,
            array_replace_recursive($baseModel, ['bio' => null]),
            $baseUser,
        ],
        'not publishable when missing connection_lived_experience' => [
            false,
            array_replace_recursive($baseModel, [
                'connection_lived_experience' => null,
                'roles' => [
                    IndividualRole::CommunityConnector->value,
                ],
            ]),
            $baseUser,
            $baseConnections,
        ],
        'not publishable when missing consulting_services' => [
            false,
            array_replace_recursive($baseModel, ['consulting_services' => null]),
            $baseUser,
        ],
        'not publishable when missing has_age_brackets' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_age_brackets' => null,
                ],
                'roles' => [
                    IndividualRole::CommunityConnector->value,
                ],
            ]),
            $baseUser,
            $baseConnections,
        ],
        'not publishable when missing has_ethnoracial_identities' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_ethnoracial_identities' => null,
                ],
                'roles' => [
                    IndividualRole::CommunityConnector->value,
                ],
            ]),
            $baseUser,
            $baseConnections,
        ],
        'not publishable when missing has_gender_and_sexual_identities' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_gender_and_sexual_identities' => null,
                ],
                'roles' => [
                    IndividualRole::CommunityConnector->value,
                ],
            ]),
            $baseUser,
            $baseConnections,
        ],
        'not publishable when missing has_indigenous_identities' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_indigenous_identities' => null,
                ],
                'roles' => [
                    IndividualRole::CommunityConnector->value,
                ],
            ]),
            $baseUser,
            $baseConnections,
        ],
        'not publishable when missing meeting_types' => [
            false,
            array_replace_recursive($baseModel, ['meeting_types' => null]),
            $baseUser,
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing name' => [
            false,
            array_replace_recursive($baseModel, ['name' => null]),
            $baseUser,
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing region' => [
            false,
            array_replace_recursive($baseModel, ['region' => null]),
            $baseUser,
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing roles' => [
            false,
            array_replace_recursive($baseModel, ['roles' => null]),
            $baseUser,
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
                'roles' => [
                    IndividualRole::CommunityConnector->value,
                ],
            ]),
            $baseUser,
            $baseConnections,
        ],
        'not publishable when missing indigenousIdentityConnections' => [
            false,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_indigenous_identities' => 1,
                ],
                'roles' => [
                    IndividualRole::CommunityConnector->value,
                ],
            ]),
            $baseUser,
            $baseConnections,
        ],
        'not publishable when missing areaTypeConnections' => [
            false,
            array_replace_recursive($baseModel, [
                'roles' => [
                    IndividualRole::CommunityConnector->value,
                ],
            ]),
            $baseUser,
            [
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing livedExperienceConnections' => [
            false,
            array_replace_recursive($baseModel, [
                'roles' => [
                    IndividualRole::CommunityConnector->value,
                ],
            ]),
            $baseUser,
            [
                'areaTypeConnections',
            ],
        ],
        'not publishable using participant role' => [
            false,
            array_replace_recursive($baseModel, ['roles' => [IndividualRole::ConsultationParticipant->value]]),
            $baseUser,
        ],
        'not publishable if user approval is pending' => [
            false,
            $baseModel,
            array_replace_recursive($baseUser, ['oriented_at' => null]),
        ],
        'publishable with all expected values' => [
            true,
            array_replace_recursive($baseModel, [
                'extra_attributes' => [
                    'has_age_brackets' => 1,
                    'has_indigenous_identities' => 1,
                ],
                'roles' => [
                    IndividualRole::CommunityConnector->value,
                ],
            ]),
            $baseUser,
            [
                'ageBracketConnections',
                'areaTypeConnections',
                'indigenousIdentityConnections',
                'livedExperienceConnections',
            ],
        ],
        'publishable without optional connections' => [
            true,
            array_replace_recursive($baseModel, [
                'roles' => [
                    IndividualRole::CommunityConnector->value,
                ],
            ]),
            $baseUser,
            $baseConnections,
        ],
    ];
});
