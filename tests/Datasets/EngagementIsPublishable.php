<?php

dataset('engagementIsPublishable', function () {
    $baseModel = [
        'name' => ['en' => 'Workshop'],
        'languages' => ['en', 'fr', 'ase', 'fcs'],
        'who' => 'individuals',
        'format' => 'workshop',
        'recruitment' => 'open-call',
        'ideal_participants' => 25,
        'minimum_participants' => 15,
        'paid' => true,
        'description' => ['en' => 'This is what we are doing'],
        'signup_by_date' => '2022-10-02',
    ];

    $interviewModel = array_merge($baseModel, [
        'format' => 'interviews',
        'window_start_date' => '2022-11-01',
        'window_end_date' => '2022-11-15',
        'window_start_time' => '09:00',
        'window_end_time' => '17:00',
        'timezone' => 'America/Toronto',
        'weekday_availabilities' => [
            'monday' => 'yes',
            'tuesday' => 'yes',
            'wednesday' => 'yes',
            'thursday' => 'yes',
            'friday' => 'yes',
            'saturday' => 'no',
            'sunday' => 'no',
        ],
        'meeting_types' => ['in_person', 'web_conference', 'phone'],
        'street_address' => '1223 Main Street',
        'locality' => 'Anytown',
        'region' => 'ON',
        'postal_code' => 'M46 17B',
        'meeting_software' => 'WebMeetingApp',
        'meeting_url' => 'https://example.com/meet',
        'meeting_phone' => '6476231847',
        'materials_by_date' => '2022-11-01',
        'complete_by_date' => '2022-11-15',
        'accepted_formats' => ['writing', 'audio', 'video'],
    ]);

    $surveyModel = array_merge($baseModel, [
        'format' => 'survey',
        'materials_by_date' => '2022-11-01',
        'complete_by_date' => '2022-11-15',
        'document_languages' => ['en', 'fr'],
    ]);

    $otherAsyncModel = array_merge($baseModel, [
        'format' => 'other-async',
        'materials_by_date' => '2022-11-01',
        'complete_by_date' => '2022-11-15',
        'document_languages' => ['en', 'fr'],
    ]);

    return [
        'not publishable without estimates and agreements' => [
            false,
            $baseModel,
            true,
            false,
        ],
        'publishable with estimates and agreements' => [
            true,
            $baseModel,
            true,
            true,
        ],
        'publishable without estimates and agreements when organization' => [
            true,
            array_replace_recursive($baseModel, [
                'format' => null,
                'recruitment' => null,
                'who' => 'organization',
                'ideal_participants' => null,
                'min_participants' => null,
            ]),
            false,
            false,
        ],
        'not publishable when projectable organization is not approved' => [
            false,
            $baseModel,
            true,
            true,
            [
                'oriented_at' => null,
                'validated_at' => null,
            ],
        ],
        'not publishable when workshop and missing meeting' => [
            false,
            $baseModel,
            false,
            true,
        ],
        'not publishable when focus group and missing meeting' => [
            false,
            array_replace_recursive($baseModel, ['format' => 'focus-group']),
            false,
            true,
        ],
        'not publishable when other synchronous and missing meeting' => [
            false,
            array_replace_recursive($baseModel, ['format' => 'other-sync']),
            false,
            true,
        ],
        'not publishable when missing description' => [
            false,
            array_replace_recursive($baseModel, ['description' => null]),
            true,
            true,
        ],
        'not publishable when interview and missing window start date' => [
            false,
            array_replace_recursive($interviewModel, ['window_start_date' => null]),
            true,
            true,
        ],

        'not publishable when interview and missing window end date' => [
            false,
            array_replace_recursive($interviewModel, ['window_end_date' => null]),
            true,
            true,
        ],

        'not publishable when interview and missing window start time' => [
            false,
            array_replace_recursive($interviewModel, ['window_start_time' => null]),
            true,
            true,
        ],

        'not publishable when interview and missing window end time' => [
            false,
            array_replace_recursive($interviewModel, ['window_end_time' => null]),
            true,
            true,
        ],

        'not publishable when interview and missing timezone' => [
            false,
            array_replace_recursive($interviewModel, ['timezone' => null]),
            true,
            true,
        ],

        'not publishable when interview and missing Monday availability' => [
            false,
            array_replace_recursive($interviewModel, ['weekday_availabilities' => ['monday' => null]]),
            true,
            true,
        ],

        'not publishable when interview and missing Tuesday availability' => [
            false,
            array_replace_recursive($interviewModel, ['weekday_availabilities' => ['tuesday' => null]]),
            true,
            true,
        ],

        'not publishable when interview and missing Wednesday availability' => [
            false,
            array_replace_recursive($interviewModel, ['weekday_availabilities' => ['wednesday' => null]]),
            true,
            true,
        ],

        'not publishable when interview and missing Thursday availability' => [
            false,
            array_replace_recursive($interviewModel, ['weekday_availabilities' => ['thursday' => null]]),
            true,
            true,
        ],

        'not publishable when interview and missing Friday availability' => [
            false,
            array_replace_recursive($interviewModel, ['weekday_availabilities' => ['friday' => null]]),
            true,
            true,
        ],

        'not publishable when interview and missing Saturday availability' => [
            false,
            array_replace_recursive($interviewModel, ['weekday_availabilities' => ['saturday' => null]]),
            true,
            true,
        ],

        'not publishable when interview and missing Sunday availability' => [
            false,
            array_replace_recursive($interviewModel, ['weekday_availabilities' => ['sunday' => null]]),
            true,
            true,
        ],

        'not publishable when interview and missing meeting types' => [
            false,
            array_replace_recursive($interviewModel, ['meeting_types' => null]),
            true,
            true,
        ],

        'not publishable when in-person interview and missing street address' => [
            false,
            array_replace_recursive($interviewModel, ['street_address' => null]),
            true,
            true,
        ],

        'not publishable when in-person interview and missing city or town' => [
            false,
            array_replace_recursive($interviewModel, ['locality' => null]),
            true,
            true,
        ],

        'not publishable when in-person interview and missing province or territory' => [
            false,
            array_replace_recursive($interviewModel, ['street_address' => null]),
            true,
            true,
        ],

        'not publishable when in-person interview and missing postal code' => [
            false,
            array_replace_recursive($interviewModel, ['postal_code' => null]),
            true,
            true,
        ],

        'not publishable when web conference interview and missing meeting software' => [
            false,
            array_replace_recursive($interviewModel, ['meeting_software' => null]),
            true,
            true,
        ],

        'not publishable when web conference interview and missing meeting URL' => [
            false,
            array_replace_recursive($interviewModel, ['meeting_url' => null]),
            true,
            true,
        ],

        'not publishable when phone interview and missing meeting phone' => [
            false,
            array_replace_recursive($interviewModel, ['meeting_phone' => null]),
            true,
            true,
        ],

        'not publishable when interview and missing materials by date' => [
            false,
            array_replace_recursive($interviewModel, ['materials_by_date' => null]),
            true,
            true,
        ],

        'not publishable when interview and missing complete by date' => [
            false,
            array_replace_recursive($interviewModel, ['complete_by_date' => null]),
            true,
            true,
        ],

        'not publishable when interview and missing accepted formats' => [
            false,
            array_replace_recursive($interviewModel, ['accepted_formats' => null]),
            true,
            true,
        ],

        'not publishable when survey and missing materials by date' => [
            false,
            array_replace_recursive($surveyModel, ['materials_by_date' => null]),
            false,
            true,
        ],

        'not publishable when survey and missing complete by date' => [
            false,
            array_replace_recursive($surveyModel, ['complete_by_date' => null]),
            false,
            true,
        ],

        'not publishable when survey and missing document languages' => [
            false,
            array_replace_recursive($surveyModel, ['document_languages' => null]),
            false,
            true,
        ],

        'not publishable when other asynchronous and missing materials by date' => [
            false,
            array_replace_recursive($otherAsyncModel, ['materials_by_date' => null]),
            true,
            true,
        ],

        'not publishable when other asynchronous and missing complete by date' => [
            false,
            array_replace_recursive($otherAsyncModel, ['complete_by_date' => null]),
            true,
            true,
        ],

        'not publishable when other asynchronous and missing document languages' => [
            false,
            array_replace_recursive($otherAsyncModel, ['document_languages' => null]),
            true,
            true,
        ],
    ];
});
