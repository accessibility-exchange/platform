<?php

use App\Enums\AcceptedFormat;
use App\Enums\Availability;
use App\Enums\EngagementFormat;
use App\Enums\MeetingType;

dataset('updateEngagementRequestValidationErrors', function () {
    return [
        'Name is missing' => [
            ['name' => null],
            fn () => [
                'name.en' => __('An engagement name must be provided in at least English or French.'),
                'name.fr' => __('An engagement name must be provided in at least English or French.'),
            ],
        ],
        'Name is missing required translation' => [
            'state' => ['name' => ['es' => 'el contrato']],
            'errors' => fn () => [
                'name.en' => __('An engagement name must be provided in at least English or French.'),
                'name.fr' => __('An engagement name must be provided in at least English or French.'),
            ],
            ['without' => ['name']],
        ],
        'Name translation is not a string' => [
            'state' => ['name.en' => false],
            'errors' => fn () => ['name.en' => __('validation.string', ['attribute' => 'name.en'])],
        ],
        'Description is missing' => [
            ['description' => null],
            fn () => [
                'description.en' => __('An engagement description must be provided in at least English or French.'),
                'description.fr' => __('An engagement description must be provided in at least English or French.'),
            ],
        ],
        'Description is missing required translation' => [
            'state' => ['description' => ['es' => 'descripción']],
            'errors' => fn () => [
                'description.en' => __('An engagement description must be provided in at least English or French.'),
                'description.fr' => __('An engagement description must be provided in at least English or French.'),
            ],
            ['without' => ['description']],
        ],
        'Description translation is not a string' => [
            'state' => ['description.en' => false],
            'errors' => fn () => ['description.en' => __('validation.string', ['attribute' => 'description.en'])],
        ],
        'Window start date is missing' => [
            ['window_start_date' => null],
            fn () => ['window_start_date' => __('You must enter a :attribute', ['attribute' => __('start date')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window start date is an invalid date' => [
            ['window_start_date' => 'someday'],
            fn () => ['window_start_date' => __('validation.date', ['attribute' => __('start date')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window start date is after end date' => [
            [
                'window_start_date' => now()->addMonths(2),
                'window_end_date' => now()->subWeek(),
            ],
            fn () => [
                'window_start_date' => __('The :attribute must be before the :date.', ['attribute' => __('start date'), 'date' => __('end date')]),
                'window_end_date' => __('The :attribute must be after the :date.', ['attribute' => __('end date'), 'date' => __('start date')]),
            ],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window end date is missing' => [
            ['window_end_date' => null],
            fn () => ['window_end_date' => __('You must enter a :attribute', ['attribute' => __('end date')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window end date is an invalid date' => [
            ['window_end_date' => 'someday'],
            fn () => ['window_end_date' => __('validation.date', ['attribute' => __('end date')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window start time is missing' => [
            ['window_start_time' => null],
            fn () => ['window_start_time' => __('You must enter a :attribute', ['attribute' => __('start time')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window start time is an invalid time' => [
            ['window_start_time' => '8:00am'],
            fn () => ['window_start_time' => __('The :attribute is not in the right format.', ['attribute' => __('start time')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window start time is after end time' => [
            [
                'window_start_time' => '11:00',
                'window_end_time' => '8:00',
            ],
            fn () => [
                'window_start_time' => __('The :attribute must be before the :date.', ['attribute' => __('start time'), 'date' => __('end time')]),
                'window_end_time' => __('The :attribute must be after the :date.', ['attribute' => __('end time'), 'date' => __('start time')]),
            ],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window end time is missing' => [
            ['window_end_time' => null],
            fn () => ['window_end_time' => __('You must enter a :attribute', ['attribute' => __('end time')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window end time is an invalid time' => [
            ['window_end_time' => '1200'],
            fn () => ['window_end_time' => __('The :attribute is not in the right format.', ['attribute' => __('end time')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Timezone is missing' => [
            ['timezone' => null],
            fn () => ['timezone' => __('You must enter a :attribute', ['attribute' => 'timezone'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Timezone is invalid' => [
            ['timezone' => 'my timezone'],
            fn () => ['timezone' => __('You must enter a :attribute', ['attribute' => 'timezone'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window flexibility is not a boolean value' => [
            ['window_flexibility' => ['false']],
            fn () => ['window_flexibility' => __('validation.boolean', ['attribute' => 'window flexibility'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Weekday availabilities is not an array' => [
            fn () => ['weekday_availabilities' => Availability::Available->value],
            fn () => [
                'weekday_availabilities' => __('validation.array', ['attribute' => 'weekday availabilities']),
                'weekday_availabilities.monday' => __('validation.required', ['attribute' => __('availability for Monday')]),
                'weekday_availabilities.tuesday' => __('validation.required', ['attribute' => __('availability for Tuesday')]),
                'weekday_availabilities.wednesday' => __('validation.required', ['attribute' => __('availability for Wednesday')]),
                'weekday_availabilities.thursday' => __('validation.required', ['attribute' => __('availability for Thursday')]),
                'weekday_availabilities.friday' => __('validation.required', ['attribute' => __('availability for Friday')]),
                'weekday_availabilities.saturday' => __('validation.required', ['attribute' => __('availability for Saturday')]),
                'weekday_availabilities.sunday' => __('validation.required', ['attribute' => __('availability for Sunday')]),
            ],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Weekday availabilities are invalid' => [
            fn () => [
                'weekday_availabilities.monday' => 'my availability',
                'weekday_availabilities.tuesday' => 'my availability',
                'weekday_availabilities.wednesday' => 'my availability',
                'weekday_availabilities.thursday' => 'my availability',
                'weekday_availabilities.friday' => 'my availability',
                'weekday_availabilities.saturday' => 'my availability',
                'weekday_availabilities.sunday' => 'my availability',
            ],
            fn () => [
                'weekday_availabilities.monday' => __('validation.in', ['attribute' => __('availability for Monday')]),
                'weekday_availabilities.tuesday' => __('validation.in', ['attribute' => __('availability for Tuesday')]),
                'weekday_availabilities.wednesday' => __('validation.in', ['attribute' => __('availability for Wednesday')]),
                'weekday_availabilities.thursday' => __('validation.in', ['attribute' => __('availability for Thursday')]),
                'weekday_availabilities.friday' => __('validation.in', ['attribute' => __('availability for Friday')]),
                'weekday_availabilities.saturday' => __('validation.in', ['attribute' => __('availability for Saturday')]),
                'weekday_availabilities.sunday' => __('validation.in', ['attribute' => __('availability for Sunday')]),
            ],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Meeting types is missing' => [
            ['meeting_types' => null],
            fn () => ['meeting_types' => __('You must select at least one way to attend the meeting.', ['attribute' => 'meeting types'])],
            [
                'format' => EngagementFormat::Interviews->value,
            ],
        ],
        'Meeting types is not an array' => [
            ['meeting_types' => MeetingType::InPerson->value],
            fn () => ['meeting_types' => __('validation.array', ['attribute' => 'meeting types'])],
            [
                'format' => EngagementFormat::Interviews->value,
            ],
        ],
        'Meeting type is invalid' => [
            ['meeting_types' => ['my meeting']],
            fn () => ['meeting_types.0' => __('You must select a valid meeting type.')],
            [
                'format' => EngagementFormat::Interviews->value,
            ],
        ],
        'Street address is missing' => [
            ['street_address' => null],
            fn () => ['street_address' => __('You must enter a :attribute for the meeting location.', ['attribute' => 'street address'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Street address is invalid' => [
            ['street_address' => false],
            fn () => ['street_address' => __('validation.string', ['attribute' => 'street address'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Unit/suite/floor is invalid' => [
            ['unit_suite_floor' => false],
            fn () => ['unit_suite_floor' => __('validation.string', ['attribute' => 'unit suite floor'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Locality is missing' => [
            ['locality' => null],
            fn () => ['locality' => __('You must enter a :attribute for the meeting location.', ['attribute' => __('city or town')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Locality is invalid' => [
            ['locality' => false],
            fn () => ['locality' => __('validation.string', ['attribute' => __('city or town')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Region is missing' => [
            ['region' => null],
            fn () => ['region' => __('You must enter a :attribute for the meeting location.', ['attribute' => __('province or territory')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Region is invalid' => [
            ['region' => 'ZZ'],
            fn () => ['region' => __('validation.in', ['attribute' => __('province or territory')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Postal code is missing' => [
            ['postal_code' => null],
            fn () => ['postal_code' => __('You must enter a :attribute for the meeting location.', ['attribute' => 'postal code'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Postal code is invalid' => [
            ['postal_code' => '123456'],
            fn () => ['postal_code' => __('validation.postal_code')],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Directions is not an array' => [
            ['directions' => 'Use the front elevator to go to the second floor.'],
            fn () => ['directions' => __('validation.array', ['attribute' => 'directions'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Meeting software is missing' => [
            ['meeting_software' => null],
            fn () => ['meeting_software' => __('You must indicate the :attribute.', ['attribute' => 'meeting software'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::WebConference->value,
            ],
        ],
        'Meeting software is invalid' => [
            ['meeting_software' => false],
            fn () => ['meeting_software' => __('validation.string', ['attribute' => 'meeting software'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::WebConference->value,
            ],
        ],
        'Alternative meeting software is invalid' => [
            ['alternative_meeting_software' => ['false']],
            fn () => ['alternative_meeting_software' => __('validation.boolean', ['attribute' => 'alternative meeting software'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::WebConference->value,
            ],
        ],
        'Meeting url is missing' => [
            ['meeting_url' => null],
            fn () => ['meeting_url' => __('You must enter a :attribute.', ['attribute' => __('link to join the meeting')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::WebConference->value,
            ],
        ],
        'Meeting url is invalid' => [
            ['meeting_url' => 'not_a_url'],
            fn () => ['meeting_url' => __('validation.url', ['attribute' => __('link to join the meeting')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::WebConference->value,
            ],
        ],
        'Additional video information is not an array' => [
            ['additional_video_information' => 'more info'],
            fn () => ['additional_video_information' => __('validation.array', ['attribute' => 'additional video information'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::WebConference->value,
            ],
        ],
        'Meeting phone number is missing' => [
            ['meeting_phone' => null],
            fn () => ['meeting_phone' => __('validation.required', ['attribute' => __('phone number to join the meeting')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::Phone->value,
            ],
        ],
        'Meeting phone number is invalid' => [
            ['meeting_phone' => '1800123456'],
            fn () => ['meeting_phone' => __('validation.phone', ['attribute' => __('phone number to join the meeting')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::Phone->value,
            ],
        ],
        'Additional phone information is not an array' => [
            ['additional_phone_information' => 'Press 1 after the beep.'],
            fn () => ['additional_phone_information' => __('validation.array', ['attribute' => 'additional phone information'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::Phone->value,
            ],
        ],
        'Materials by date missing - interviews' => [
            ['materials_by_date' => null],
            fn () => ['materials_by_date' => __('You must enter a :attribute.', ['attribute' => __('date for materials to be sent by')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Materials by date is invalid - interviews' => [
            ['materials_by_date' => 'someday'],
            fn () => ['materials_by_date' => __('Please enter a valid :attribute.', ['attribute' => __('date for materials to be sent by')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Materials by date is after completed date - interviews' => [
            [
                'materials_by_date' => now()->addMonth(5),
                'complete_by_date' => now()->addMonth(4),
            ],
            fn () => [
                'materials_by_date' => __('The :attribute must be before the :date.', ['attribute' => __('date for materials to be sent by'), 'date' => __('due date')]),
                'complete_by_date' => __('The :attribute must be after the :date.', ['attribute' => __('due date'), 'date' => __('date for materials to be sent by')]),
            ],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Materials by date missing - other-async' => [
            ['materials_by_date' => null],
            fn () => ['materials_by_date' => __('You must enter a :attribute.', ['attribute' => __('date for materials to be sent by')])],
            [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Materials by date is invalid - other-async' => [
            ['materials_by_date' => 'someday'],
            fn () => ['materials_by_date' => __('Please enter a valid :attribute.', ['attribute' => __('date for materials to be sent by')])],
            [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Materials by date is after completed date - other-async' => [
            [
                'materials_by_date' => now()->addMonth(5),
                'complete_by_date' => now()->addMonth(4),
            ],
            fn () => [
                'materials_by_date' => __('The :attribute must be before the :date.', ['attribute' => __('date for materials to be sent by'), 'date' => __('due date')]),
                'complete_by_date' => __('The :attribute must be after the :date.', ['attribute' => __('due date'), 'date' => __('date for materials to be sent by')]),
            ],
            [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Materials by date missing - survey' => [
            ['materials_by_date' => null],
            fn () => ['materials_by_date' => __('You must enter a :attribute.', ['attribute' => __('date for materials to be sent by')])],
            [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Materials by date is invalid - survey' => [
            ['materials_by_date' => 'someday'],
            fn () => ['materials_by_date' => __('Please enter a valid :attribute.', ['attribute' => __('date for materials to be sent by')])],
            [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Materials by date is after completed date - survey' => [
            [
                'materials_by_date' => now()->addMonth(5),
                'complete_by_date' => now()->addMonth(4),
            ],
            fn () => [
                'materials_by_date' => __('The :attribute must be before the :date.', ['attribute' => __('date for materials to be sent by'), 'date' => __('due date')]),
                'complete_by_date' => __('The :attribute must be after the :date.', ['attribute' => __('due date'), 'date' => __('date for materials to be sent by')]),
            ],
            [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Complete by date missing - interviews' => [
            ['complete_by_date' => null],
            fn () => ['complete_by_date' => __('You must enter a :attribute.', ['attribute' => __('due date')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Complete by date is invalid - interviews' => [
            ['complete_by_date' => 'someday'],
            fn () => ['complete_by_date' => __('Please enter a valid :attribute.', ['attribute' => __('due date')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Complete by date missing - other-async' => [
            ['complete_by_date' => null],
            fn () => ['complete_by_date' => __('You must enter a :attribute.', ['attribute' => __('due date')])],
            [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Complete by date is invalid - other-async' => [
            ['complete_by_date' => 'someday'],
            fn () => ['complete_by_date' => __('Please enter a valid :attribute.', ['attribute' => __('due date')])],
            [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Complete by date missing - survey' => [
            ['complete_by_date' => null],
            fn () => ['complete_by_date' => __('You must enter a :attribute.', ['attribute' => __('due date')])],
            [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Complete by date is invalid - survey' => [
            ['complete_by_date' => 'someday'],
            fn () => ['complete_by_date' => __('Please enter a valid :attribute.', ['attribute' => __('due date')])],
            [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Document languages missing - other-async' => [
            ['document_languages' => null],
            fn () => ['document_languages' => __('Please select a language that the engagement documents will be in.')],
            [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Document languages not an array - other-async' => [
            ['document_languages' => 'en'],
            fn () => ['document_languages' => __('validation.array', ['attribute' => 'document languages'])],
            [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Document language is invalid - other-async' => [
            ['document_languages' => ['xx']],
            fn () => ['document_languages.0' => __('Please select a language that the engagement documents will be in.')],
            [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Document languages missing - survey' => [
            ['document_languages' => null],
            fn () => ['document_languages' => __('Please select a language that the engagement documents will be in.')],
            [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Document languages not an array - survey' => [
            ['document_languages' => 'en'],
            fn () => ['document_languages' => __('validation.array', ['attribute' => 'document languages'])],
            [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Document language is invalid - survey' => [
            ['document_languages' => ['xx']],
            fn () => ['document_languages.0' => __('Please select a language that the engagement documents will be in.')],
            [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Accepted formats missing' => [
            ['accepted_formats' => null],
            fn () => ['accepted_formats' => __('You must indicate the :attribute.', ['attribute' => 'accepted formats'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['other_accepted_format'],
            ],
        ],
        'Accepted formats is not an array' => [
            ['accepted_formats' => AcceptedFormat::Writing->value],
            fn () => ['accepted_formats' => __('validation.array', ['attribute' => 'accepted formats'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['other_accepted_format'],
            ],
        ],
        'Accepted format is invalid' => [
            ['accepted_formats' => ['Text']],
            fn () => ['accepted_formats.0' => __('You must select a valid format.')],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['other_accepted_format'],
            ],
        ],
        'Other accepted formats is not a boolean' => [
            ['other_accepted_formats' => 'false'],
            fn () => ['other_accepted_formats' => __('validation.boolean', ['attribute' => __('accepted formats')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Other accepted format is not a string' => [
            [
                'other_accepted_formats' => true,
                'other_accepted_format' => ['en' => false],
            ],
            fn () => ['other_accepted_format.en' => __('The other accepted format must be a string.')],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Other accepted format is missing required translation' => [
            [
                'other_accepted_formats' => true,
                'other_accepted_format' => ['es' => 'la escritura'],
            ],
            fn () => [
                'other_accepted_format.en' => __('The other accepted format must be provided in at least English or French.'),
                'other_accepted_format.fr' => __('The other accepted format must be provided in at least English or French.'),
            ],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['other_accepted_format.en'],
            ],
        ],
        'Open to other formats is not a boolean' => [
            ['open_to_other_formats' => 'ture'],
            fn () => ['open_to_other_formats' => __('validation.boolean', ['attribute' => 'open to other formats'])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Paid is not a boolean' => [
            ['paid' => 'false'],
            fn () => ['paid' => __('validation.boolean', ['attribute' => 'paid'])],
        ],
        'Signup by date is missing' => [
            ['signup_by_date' => null],
            fn () => ['signup_by_date' => __('You must enter a :attribute.', ['attribute' => __('sign up deadline')])],
        ],
        'Signup by date is an invalid date' => [
            ['signup_by_date' => 'someday'],
            fn () => ['signup_by_date' => __('Please enter a valid date for the :attribute.', ['attribute' => __('sign up deadline')])],
        ],
        'Signup by date after window start date' => [
            [
                'window_start_date' => now()->addMonth(1),
                'signup_by_date' => now()->addMonth(2),
            ],
            fn () => ['signup_by_date' => __('The :attribute must be before the :date.', ['attribute' => __('sign up deadline'), 'date' => __('start date')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Signup by date after materials by date' => [
            [
                'materials_by_date' => now()->addMonth(1),
                'signup_by_date' => now()->addMonth(2),
            ],
            fn () => ['signup_by_date' => __('The :attribute must be before the :date.', ['attribute' => __('sign up deadline'), 'date' => __('date for materials to be sent by')])],
            [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
    ];
});
