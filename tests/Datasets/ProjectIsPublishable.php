<?php

dataset('projectIsPublishable', function () {
    $baseModel = [
        'contact_person_phone' => '4165555555',
        'contact_person_response_time' => ['en' => '48 hours'],
        'preferred_contact_method' => 'required',
        'team_languages' => ['en'],
        'team_trainings' => [
            [
                'date' => date('Y-m-d', time()),
                'name' => 'test training',
                'trainer_name' => 'trainer',
                'trainer_url' => 'http://example.com',
            ],
        ],
    ];

    return [
        'not publishable when contact_person_email' => [
            false,
            array_replace_recursive($baseModel, [
                'contact_person_email' => null,
                'contact_person_phone' => null,
            ]),
        ],
        'not publishable when missing contact_person_name' => [
            false,
            array_replace_recursive($baseModel, ['contact_person_name' => null]),
        ],
        'not publishable when missing contact_person_phone' => [
            false,
            array_replace_recursive($baseModel, [
                'contact_person_phone' => null,
                'contact_person_vrs' => true,
            ]),
        ],
        'not publishable when missing contact_person_response_time' => [
            false,
            array_replace_recursive($baseModel, ['contact_person_response_time' => null]),
        ],
        'not publishable when missing end_date' => [
            false,
            array_replace_recursive($baseModel, ['end_date' => null]),
        ],
        'not publishable when missing goals' => [
            false,
            array_replace_recursive($baseModel, ['goals' => null]),
        ],
        'not publishable when missing languages' => [
            false,
            array_replace_recursive($baseModel, ['languages' => null]),
        ],
        'not publishable when missing name' => [
            false,
            array_replace_recursive($baseModel, ['name' => null]),
        ],
        'not publishable when missing preferred_contact_method' => [
            false,
            array_replace_recursive($baseModel, ['preferred_contact_method' => null]),
        ],
        'not publishable when missing regions' => [
            false,
            array_replace_recursive($baseModel, ['regions' => null]),
        ],
        'not publishable when missing scope' => [
            false,
            array_replace_recursive($baseModel, ['scope' => null]),
        ],
        'not publishable when missing start_date' => [
            false,
            array_replace_recursive($baseModel, ['start_date' => null]),
        ],
        'not publishable when missing team_languages' => [
            false,
            array_replace_recursive($baseModel, ['team_languages' => null]),
        ],
        'not publishable when missing team_trainings.*.date' => [
            false,
            array_replace_recursive($baseModel, [
                'team_trainings' => [
                    ['date' => null],
                ],
            ]),
        ],
        'not publishable when missing team_trainings.*.name' => [
            false,
            array_replace_recursive($baseModel, [
                'team_trainings' => [
                    ['name' => null],
                ],
            ]),
        ],
        'not publishable when missing team_trainings.*.trainer_name' => [
            false,
            array_replace_recursive($baseModel, [
                'team_trainings' => [
                    ['trainer_name' => null],
                ],
            ]),
        ],
        'not publishable when missing team_trainings.*.trainer_url' => [
            false,
            array_replace_recursive($baseModel, [
                'team_trainings' => [
                    ['trainer_url' => null],
                ],
            ]),
        ],
        'not publishable when missing impacts as Regulated Organization' => [
            false,
            $baseModel,
            [],
            'regulated-organization',
        ],
        'publishable with all expected values' => [
            true,
            $baseModel,
        ],
        'publishable with impacts as a Regulated Organization' => [
            true,
            $baseModel,
            ['impacts'],
            'regulated-organization',
        ],
    ];
});
