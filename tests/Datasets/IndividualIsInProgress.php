<?php

use App\Enums\CommunityConnectorHasLivedExperience;
use App\Enums\ConsultingService;
use App\Enums\MeetingType;
use App\Enums\ProvinceOrTerritory;
use Illuminate\Support\Arr;

dataset('individualIsInProgress', function () {
    $inProgress = true;
    $notStarted = false;
    $add_identity = true;
    $dont_add_identity = false;

    $baseData = [
        'pronouns' => null,
        'bio' => null,
        'region' => null,
        'locality' => null,
        'working_languages' => null,
        'consulting_services' => null,
        'social_links' => null,
        'website_link' => null,
        'other_disability_connection' => null,
        'other_ethnoracial_identity_connection' => null,
        'connection_lived_experience' => null,
        'lived_experience' => null,
        'skills_and_strengths' => null,
        'relevant_experiences' => null,
        'meeting_types' => null,
        'extra_attributes' => [],
    ];

    $filledData = [
        'pronouns' => ['she', 'her'],
        'bio' => 'This is my bio',
        'region' => ProvinceOrTerritory::NovaScotia->value,
        'locality' => 'Halifax',
        'working_languages' => ['en'],
        'consulting_services' => [
            ConsultingService::DesigningConsultation->value,
            ConsultingService::RunningConsultation->value,
        ],
        'social_links' => [
            'linked_in' => 'https://linkedin.com/in/someone',
        ],
        'website_link' => 'https://example.com',
        'other_disability_connection' => 'Something not listed',
        'other_ethnoracial_identity_connection' => 'Something else',
        'connection_lived_experience' => CommunityConnectorHasLivedExperience::YesAll->value,
        'lived_experience' => 'My lived experiences.',
        'skills_and_strengths' => 'My skills and strengths.',
        'relevant_experiences' => [
            [
                'title' => 'First job',
                'organization' => 'First place',
                'start_year' => '2021',
                'end_year' => '',
                'current' => 1,
            ],
        ],
        'meeting_types' => [
            MeetingType::InPerson->value,
            MeetingType::WebConference->value,
        ],
        'extra_attributes' => ['cross_disability_and_deaf_connections' => 1],
    ];

    $cases = [
        'Not started' => [
            $baseData,
            $dont_add_identity,
            $notStarted,
        ],
        'Only identity added' => [
            $baseData,
            $add_identity,
            $inProgress,
        ],
    ];

    foreach ($filledData as $prop => $value) {
        $cases["Only {$prop} set"] = [
            array_replace_recursive($baseData, Arr::only($filledData, [$prop])),
            $dont_add_identity,
            $inProgress,
        ];
    }

    return $cases;
});
