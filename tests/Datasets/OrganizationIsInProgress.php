<?php

use App\Enums\ConsultingService;
use App\Enums\ProvinceOrTerritory;
use App\Enums\StaffHaveLivedExperience;
use Illuminate\Support\Arr;

dataset('organizationIsInProgress', function () {
    $inProgress = true;
    $notStarted = false;
    $add_identity = true;
    $dont_add_identity = false;

    $baseData = [
        'region' => null,
        'locality' => null,
        'about' => null,
        'service_areas' => null,
        'consulting_services' => null,
        'social_links' => null,
        'website_link' => null,
        'other_disability_constituency' => null,
        'other_ethnoracial_identity_constituency' => null,
        'staff_lived_experience' => null,
        'extra_attributes' => [],
    ];

    $filledData = [
        'region' => ProvinceOrTerritory::NovaScotia->value,
        'locality' => 'Halifax',
        'about' => 'About this org',
        'service_areas' => [ProvinceOrTerritory::NovaScotia->value],
        'consulting_services' => [
            ConsultingService::DesigningConsultation->value,
            ConsultingService::RunningConsultation->value,
        ],
        'social_links' => [
            'linked_in' => 'https://linkedin.com/in/someone',
        ],
        'website_link' => 'https://example.com',
        'other_disability_constituency' => 'Something not listed',
        'other_ethnoracial_identity_constituency' => 'Something else',
        'staff_lived_experience' => StaffHaveLivedExperience::PreferNotToAnswer->value,
        'extra_attributes' => ['disability_and_deaf_constituencies' => 1],
    ];

    $cases = [
        'Not started' => [
            $baseData,
            $dont_add_identity,
            $notStarted,
        ],
        'Only constituent identity added' => [
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
