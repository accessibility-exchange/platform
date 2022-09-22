<?php

use App\Enums\CommunityConnectorHasLivedExperience;
use App\Enums\ConsultingService;
use App\Enums\IndividualRole;
use App\Enums\MeetingType;

dataset('individualIsPublishable', function () {
    return [
        'not publishable when missing bio' => [
            false,
            [
                'bio' => null,
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
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing connection_lived_experience' => [
            false,
            [
                'bio' => 'test bio',
                'connection_lived_experience' => null,
                'consulting_services' => [ConsultingService::Analysis->value],
                'extra_attributes' => [
                    'has_age_brackets' => 0,
                    'has_ethnoracial_identities' => 0,
                    'has_gender_and_sexual_identities' => 0,
                    'has_indigenous_identities' => 0,
                ],
                'meeting_types' => [MeetingType::InPerson->value],
                'roles' => [IndividualRole::AccessibilityConsultant->value],
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing consulting_services' => [
            false,
            [
                'bio' => 'test bio',
                'connection_lived_experience' => CommunityConnectorHasLivedExperience::YesAll->value,
                'consulting_services' => null,
                'extra_attributes' => [
                    'has_age_brackets' => 0,
                    'has_ethnoracial_identities' => 0,
                    'has_gender_and_sexual_identities' => 0,
                    'has_indigenous_identities' => 0,
                ],
                'meeting_types' => [MeetingType::InPerson->value],
                'roles' => [IndividualRole::AccessibilityConsultant->value],
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing has_age_brackets' => [
            false,
            [
                'bio' => 'test bio',
                'connection_lived_experience' => CommunityConnectorHasLivedExperience::YesAll->value,
                'consulting_services' => [ConsultingService::Analysis->value],
                'extra_attributes' => [
                    'has_age_brackets' => null,
                    'has_ethnoracial_identities' => 0,
                    'has_gender_and_sexual_identities' => 0,
                    'has_indigenous_identities' => 0,
                ],
                'meeting_types' => [MeetingType::InPerson->value],
                'roles' => [IndividualRole::AccessibilityConsultant->value],
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing has_ethnoracial_identities' => [
            false,
            [
                'bio' => 'test bio',
                'connection_lived_experience' => CommunityConnectorHasLivedExperience::YesAll->value,
                'consulting_services' => [ConsultingService::Analysis->value],
                'extra_attributes' => [
                    'has_age_brackets' => 0,
                    'has_ethnoracial_identities' => null,
                    'has_gender_and_sexual_identities' => 0,
                    'has_indigenous_identities' => 0,
                ],
                'meeting_types' => [MeetingType::InPerson->value],
                'roles' => [IndividualRole::AccessibilityConsultant->value],
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing has_gender_and_sexual_identities' => [
            false,
            [
                'bio' => 'test bio',
                'connection_lived_experience' => CommunityConnectorHasLivedExperience::YesAll->value,
                'consulting_services' => [ConsultingService::Analysis->value],
                'extra_attributes' => [
                    'has_age_brackets' => 0,
                    'has_ethnoracial_identities' => 0,
                    'has_gender_and_sexual_identities' => null,
                    'has_indigenous_identities' => 0,
                ],
                'meeting_types' => [MeetingType::InPerson->value],
                'roles' => [IndividualRole::AccessibilityConsultant->value],
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing has_indigenous_identities' => [
            false,
            [
                'bio' => 'test bio',
                'connection_lived_experience' => CommunityConnectorHasLivedExperience::YesAll->value,
                'consulting_services' => [ConsultingService::Analysis->value],
                'extra_attributes' => [
                    'has_age_brackets' => 0,
                    'has_ethnoracial_identities' => 0,
                    'has_gender_and_sexual_identities' => 0,
                    'has_indigenous_identities' => null,
                ],
                'meeting_types' => [MeetingType::InPerson->value],
                'roles' => [IndividualRole::AccessibilityConsultant->value],
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing meeting_types' => [
            false,
            [
                'bio' => 'test bio',
                'connection_lived_experience' => CommunityConnectorHasLivedExperience::YesAll->value,
                'consulting_services' => [ConsultingService::Analysis->value],
                'extra_attributes' => [
                    'has_age_brackets' => 0,
                    'has_ethnoracial_identities' => 0,
                    'has_gender_and_sexual_identities' => 0,
                    'has_indigenous_identities' => 0,
                ],
                'meeting_types' => null,
                'roles' => [IndividualRole::AccessibilityConsultant->value],
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing name' => [
            false,
            [
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
                'name' => null,
                'roles' => [IndividualRole::AccessibilityConsultant->value],
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing region' => [
            false,
            [
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
                'region' => null,
                'roles' => [IndividualRole::AccessibilityConsultant->value],
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing roles' => [
            false,
            [
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
                'roles' => null,
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing ageBracketConnections' => [
            false,
            [
                'bio' => 'test bio',
                'connection_lived_experience' => CommunityConnectorHasLivedExperience::YesAll->value,
                'consulting_services' => [ConsultingService::Analysis->value],
                'extra_attributes' => [
                    'has_age_brackets' => 1,
                    'has_ethnoracial_identities' => 0,
                    'has_gender_and_sexual_identities' => 0,
                    'has_indigenous_identities' => 0,
                ],
                'meeting_types' => [MeetingType::InPerson->value],
                'roles' => [IndividualRole::AccessibilityConsultant->value],
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing indigenousIdentityConnections' => [
            false,
            [
                'bio' => 'test bio',
                'connection_lived_experience' => CommunityConnectorHasLivedExperience::YesAll->value,
                'consulting_services' => [ConsultingService::Analysis->value],
                'extra_attributes' => [
                    'has_age_brackets' => 0,
                    'has_ethnoracial_identities' => 0,
                    'has_gender_and_sexual_identities' => 0,
                    'has_indigenous_identities' => 1,
                ],
                'meeting_types' => [MeetingType::InPerson->value],
                'roles' => [IndividualRole::AccessibilityConsultant->value],
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing areaTypeConnections' => [
            false,
            [
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
            ],
            [
                'livedExperienceConnections',
            ],
        ],
        'not publishable when missing livedExperienceConnections' => [
            false,
            [
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
            ],
            [
                'areaTypeConnections',
            ],
        ],
        'not publishable using participant role' => [
            false,
            [
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
                'roles' => [IndividualRole::ConsultationParticipant->value],
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'publishable with all expected values' => [
            true,
            [
                'bio' => 'test bio',
                'connection_lived_experience' => CommunityConnectorHasLivedExperience::YesAll->value,
                'consulting_services' => [ConsultingService::Analysis->value],
                'extra_attributes' => [
                    'has_age_brackets' => 1,
                    'has_ethnoracial_identities' => 0,
                    'has_gender_and_sexual_identities' => 0,
                    'has_indigenous_identities' => 1,
                ],
                'meeting_types' => [MeetingType::InPerson->value],
                'roles' => [IndividualRole::AccessibilityConsultant->value],
            ],
            [
                'ageBracketConnections',
                'areaTypeConnections',
                'indigenousIdentityConnections',
                'livedExperienceConnections',
            ],
        ],
        'publishable without optional connections' => [
            true,
            [
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
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
        'publishable using connector role' => [
            true,
            [
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
                'roles' => [IndividualRole::CommunityConnector->value],
            ],
            [
                'areaTypeConnections',
                'livedExperienceConnections',
            ],
        ],
    ];
});
