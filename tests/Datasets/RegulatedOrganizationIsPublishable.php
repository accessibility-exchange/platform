<?php

use App\Enums\ProvinceOrTerritory;

dataset('regulatedOrganizationIsPublishable', function () {
    $baseModel = [
        'about' => 'test regulated organization about',
        'accessibility_and_inclusion_links' => [
            'en' => [
                'title' => 'test title',
                'url' => 'http://example.com/en/',
            ],
        ],
        'contact_person_phone' => '4165555555',
        'locality' => 'Toronto',
        'preferred_contact_method' => 'email',
        'region' => [ProvinceOrTerritory::Ontario->value],
        'service_areas' => [ProvinceOrTerritory::Ontario->value],
    ];

    return [
        'not publishable when missing about' => [
            false,
            array_replace_recursive($baseModel, ['about' => null]),
            ['sector'],
        ],
        'not publishable when missing accessibility_and_inclusion_links.*.title' => [
            false,
            array_replace_recursive($baseModel, [
                'accessibility_and_inclusion_links' => [
                    'en' => [
                        'title' => null,
                    ],
                ],
            ]),
            ['sector'],
        ],
        'not publishable when missing accessibility_and_inclusion_links.*.url' => [
            false,
            array_replace_recursive($baseModel, [
                'accessibility_and_inclusion_links' => [
                    'en' => [
                        'url' => null,
                    ],
                ],
            ]),
            ['sector'],
        ],
        'not publishable when missing contact_person_email' => [
            false,
            array_replace_recursive($baseModel, [
                'contact_person_email' => null,
                'contact_person_phone' => null,
            ]),
            ['sector'],
        ],
        'not publishable when missing contact_person_name' => [
            false,
            array_replace_recursive($baseModel, ['contact_person_name' => null]),
            ['sector'],
        ],
        'not publishable when missing contact_person_phone' => [
            false,
            array_replace_recursive($baseModel, [
                'contact_person_phone' => null,
                'contact_person_vrs' => true,
            ]),
            ['sector'],
        ],
        'not publishable when missing languages' => [
            false,
            array_replace_recursive($baseModel, ['languages' => null]),
            ['sector'],
        ],
        'not publishable when missing locality' => [
            false,
            array_replace_recursive($baseModel, ['locality' => null]),
            ['sector'],
        ],
        'not publishable when missing name' => [
            false,
            array_replace_recursive($baseModel, ['name' => null]),
            ['sector'],
        ],
        'not publishable when missing preferred_contact_method' => [
            false,
            array_replace_recursive($baseModel, ['preferred_contact_method' => null]),
            ['sector'],
        ],
        'not publishable when missing region' => [
            false,
            array_replace_recursive($baseModel, ['region' => null]),
            ['sector'],
        ],
        'not publishable when missing service_areas' => [
            false,
            array_replace_recursive($baseModel, ['service_areas' => null]),
            ['sector'],
        ],
        'not publishable when missing type' => [
            false,
            array_replace_recursive($baseModel, ['type' => null]),
            ['sector'],
        ],
        'not publishable when missing sector' => [
            false,
            $baseModel,
        ],
        'publishable with all expected values' => [
            true,
            $baseModel,
            ['sector'],
        ],
        'publishable without accessibility_and_inclusion_links' => [
            true,
            array_replace_recursive($baseModel, [
                'accessibility_and_inclusion_links' => null,
            ]),
            ['sector'],
        ],
        'publishable with only e-mail contact' => [
            true,
            array_replace_recursive($baseModel, [
                'contact_person_phone' => null,
                'preferred_contact_method' => 'email',
            ]),
            ['sector'],
        ],
        'publishable with only phone contact' => [
            true,
            array_replace_recursive($baseModel, [
                'contact_person_email' => null,
                'preferred_contact_method' => 'phone',
            ]),
            ['sector'],
        ],
    ];
});
