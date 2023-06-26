<?php

use App\Enums\MeetingType;

dataset('meetingRequestValidationErrors', function () {
    return [
        'Title missing required translation' => [
            ['title' => ['es' => 'la sesión']],
            fn () => [
                'title.en' => __('A meeting title must be provided in at least one language.'),
                'title.fr' => __('A meeting title must be provided in at least one language.'),
            ],
            [
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['title.en'],
            ],
        ],
        'Title is not a string' => [
            ['title' => ['en' => false]],
            fn () => ['title.en' => __('validation.string', ['attribute' => 'title.en'])],
            ['meetingType' => MeetingType::InPerson->value],
        ],
        'Date missing' => [
            ['date' => null],
            fn () => ['date' => __('validation.required', ['attribute' => __('meeting date')])],
            [
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['date'],
            ],
        ],
        'Date is invalid' => [
            ['date' => 'someday'],
            fn () => ['date' => __('validation.date', ['attribute' => __('meeting date')])],
            ['meetingType' => MeetingType::InPerson->value],
        ],
        'Start time missing' => [
            ['start_time' => null],
            fn () => ['start_time' => __('validation.required', ['attribute' => __('meeting start time')])],
            [
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['start_time'],
            ],
        ],
        'Start time is invalid' => [
            ['start_time' => '8:00am'],
            fn () => ['start_time' => __('The :attribute format is not valid.', ['attribute' => __('meeting start time')])],
            ['meetingType' => MeetingType::InPerson->value],
        ],
        'Start time is after end time' => [
            [
                'start_time' => '22:00',
                'end_time' => '7:00',
            ],
            fn () => [
                'start_time' => __('validation.before', ['attribute' => __('meeting start time'), 'date' => __('meeting end time')]),
                'end_time' => __('validation.after', ['attribute' => __('meeting end time'), 'date' => __('meeting start time')]),
            ],
            ['meetingType' => MeetingType::InPerson->value],
        ],
        'End time missing' => [
            ['end_time' => null],
            fn () => ['end_time' => __('validation.required', ['attribute' => __('meeting end time')])],
            [
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['end_time'],
            ],
        ],
        'End time is invalid' => [
            ['end_time' => '8:00am'],
            fn () => ['end_time' => __('The :attribute format is not valid.', ['attribute' => __('meeting end time')])],
            ['meetingType' => MeetingType::InPerson->value],
        ],
        'Meeting types missing' => [
            ['meeting_types' => null],
            fn () => ['meeting_types' => __('You must indicate at least one way for participants to attend the meeting.')],
            ['without' => ['meeting_types']],
        ],
        'Meeting types is not an array' => [
            ['meeting_types' => MeetingType::InPerson->value],
            fn () => ['meeting_types' => __('validation.array', ['attribute' => 'meeting types'])],
        ],
        'Meeting type is invalid' => [
            ['meeting_types' => ['new_meeting_type']],
            fn () => ['meeting_types.0' => __('You must select a valid meeting type.')],
        ],
        'Street address missing' => [
            ['street_address' => null],
            fn () => ['street_address' => __('You must enter a :attribute for the meeting location.', ['attribute' => 'street address'])],
            [
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['street_address'],
            ],
        ],
        'Street address is not a string' => [
            ['street_address' => 1234],
            fn () => ['street_address' => __('validation.string', ['attribute' => 'street address'])],
            ['meetingType' => MeetingType::InPerson->value],
        ],
        'Unit/Suite/Floor is not a string' => [
            ['unit_suite_floor' => 1234],
            fn () => ['unit_suite_floor' => __('validation.string', ['attribute' => 'unit suite floor'])],
            ['meetingType' => MeetingType::InPerson->value],
        ],
        'Locality missing' => [
            ['locality' => null],
            fn () => ['locality' => __('You must enter a :attribute for the meeting location.', ['attribute' => 'locality'])],
            [
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['locality'],
            ],
        ],
        'Locality is not a string' => [
            ['locality' => 1234],
            fn () => ['locality' => __('validation.string', ['attribute' => 'locality'])],
            ['meetingType' => MeetingType::InPerson->value],
        ],
        'Region missing' => [
            ['region' => null],
            fn () => ['region' => __('You must enter a :attribute for the meeting location.', ['attribute' => __('province or territory')])],
            [
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['region'],
            ],
        ],
        'Region is not a string' => [
            ['region' => 'XX'],
            fn () => ['region' => __('validation.in', ['attribute' => __('province or territory')])],
            ['meetingType' => MeetingType::InPerson->value],
        ],
        'Postal code missing' => [
            ['postal_code' => null],
            fn () => ['postal_code' => __('You must enter a :attribute for the meeting location.', ['attribute' => 'postal code'])],
            [
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['postal_code'],
            ],
        ],
        'Postal code is not a string' => [
            ['postal_code' => 'XX'],
            fn () => ['postal_code' => __('validation.postal_code', ['attribute' => 'postal code'])],
            ['meetingType' => MeetingType::InPerson->value],
        ],
        'Directions is not an array' => [
            ['directions' => 'Take first elevator to the second floor.'],
            fn () => ['directions' => __('validation.array', ['attribute' => 'directions'])],
            ['meetingType' => MeetingType::InPerson->value],
        ],
        'Meeting software missing' => [
            ['meeting_software' => null],
            fn () => ['meeting_software' => __('You must indicate the :attribute.', ['attribute' => 'meeting software'])],
            [
                'meetingType' => MeetingType::WebConference->value,
                'without' => ['meeting_software'],
            ],
        ],
        'Meeting software is not a string' => [
            ['meeting_software' => 1234],
            fn () => ['meeting_software' => __('validation.string', ['attribute' => 'meeting software'])],
            ['meetingType' => MeetingType::WebConference->value],
        ],
        'Alternative meeting software is not a boolean' => [
            ['alternative_meeting_software' => 'false'],
            fn () => ['alternative_meeting_software' => __('validation.boolean', ['attribute' => 'alternative meeting software'])],
            ['meetingType' => MeetingType::WebConference->value],
        ],
        'Meeting url missing' => [
            ['meeting_url' => null],
            fn () => ['meeting_url' => __('You must provide a :attribute.', ['attribute' => __('link to join the meeting')])],
            [
                'meetingType' => MeetingType::WebConference->value,
                'without' => ['meeting_url'],
            ],
        ],
        'Meeting url is not a valid url' => [
            ['meeting_url' => 'zoom'],
            fn () => ['meeting_url' => __('validation.url', ['attribute' => __('link to join the meeting')])],
            ['meetingType' => MeetingType::WebConference->value],
        ],
        'Additional video information is not an array' => [
            ['additional_video_information' => 'Wait for host to accept you to the room.'],
            fn () => ['additional_video_information' => __('validation.array', ['attribute' => 'additional video information'])],
            ['meetingType' => MeetingType::WebConference->value],
        ],
        'Meeting phone missing' => [
            ['meeting_phone' => null],
            fn () => ['meeting_phone' => __('validation.required', ['attribute' => __('meeting phone number')])],
            [
                'meetingType' => MeetingType::Phone->value,
                'without' => ['meeting_phone'],
            ],
        ],
        'Meeting phone is not a valid phone number' => [
            ['meeting_phone' => '1800123456'],
            fn () => ['meeting_phone' => __('validation.phone', ['attribute' => __('meeting phone number')])],
            ['meetingType' => MeetingType::Phone->value],
        ],
        'additional_phone_information' => [
            ['additional_phone_information' => 'Press option 1.'],
            fn () => ['additional_phone_information' => __('validation.array', ['attribute' => 'additional phone information'])],
            ['meetingType' => MeetingType::Phone->value],
        ],
    ];
});
