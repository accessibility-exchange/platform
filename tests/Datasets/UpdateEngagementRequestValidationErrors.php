<?php

use App\Enums\AcceptedFormat;
use App\Enums\Availability;
use App\Enums\EngagementFormat;
use App\Enums\MeetingType;

dataset('updateEngagementRequestValidationErrors', function () {
    return [
        'Name is missing' => fn () => [
            'state' => ['name' => null],
            'errors' => [
                'name.en' => __('An engagement name must be provided in either English or French.'),
                'name.fr' => __('An engagement name must be provided in either English or French.'),
            ],
        ],
        'Name is missing required translation' => fn () => [
            'state' => ['name' => ['es' => 'el contrato']],
            'errors' => [
                'name.en' => __('An engagement name must be provided in either English or French.'),
                'name.fr' => __('An engagement name must be provided in either English or French.'),
            ],
            'modifiers' => ['without' => ['name']],
        ],
        'Name translation is not a string' => fn () => [
            'state' => ['name.en' => false],
            'errors' => ['name.en' => __('validation.string', ['attribute' => __('engagement name (English)')])],
        ],
        'Description is missing' => fn () => [
            'state' => ['description' => null],
            'errors' => [
                'description.en' => __('An engagement description must be provided in either English or French.'),
                'description.fr' => __('An engagement description must be provided in either English or French.'),
            ],
        ],
        'Description is missing required translation' => fn () => [
            'state' => ['description' => ['es' => 'descripción']],
            'errors' => [
                'description.en' => __('An engagement description must be provided in either English or French.'),
                'description.fr' => __('An engagement description must be provided in either English or French.'),
            ],
            'modifiers' => ['without' => ['description']],
        ],
        'Description translation is not a string' => fn () => [
            'state' => ['description.en' => false],
            'errors' => ['description.en' => __('validation.string', ['attribute' => __('engagement description (English)')])],
        ],
        'Window start date is missing' => fn () => [
            'state' => ['window_start_date' => null],
            'errors' => ['window_start_date' => __('You must enter a :attribute', ['attribute' => __('start date')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window start date is an invalid date' => fn () => [
            'state' => ['window_start_date' => 'someday'],
            'errors' => ['window_start_date' => __('validation.date', ['attribute' => __('start date')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window start date is after end date' => fn () => [
            'state' => [
                'window_start_date' => now()->addMonths(2),
                'window_end_date' => now()->subWeek(),
            ],
            'errors' => [
                'window_start_date' => __('The :attribute must be before the :date.', ['attribute' => __('start date'), 'date' => __('end date')]),
                'window_end_date' => __('The :attribute must be after the :date.', ['attribute' => __('end date'), 'date' => __('start date')]),
            ],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window end date is missing' => fn () => [
            'state' => ['window_end_date' => null],
            'errors' => ['window_end_date' => __('You must enter a :attribute', ['attribute' => __('end date')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window end date is an invalid date' => fn () => [
            'state' => ['window_end_date' => 'someday'],
            'errors' => ['window_end_date' => __('validation.date', ['attribute' => __('end date')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window start time is missing' => fn () => [
            'state' => ['window_start_time' => null],
            'errors' => ['window_start_time' => __('You must enter a :attribute', ['attribute' => __('start time')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window start time is an invalid time' => fn () => [
            'state' => ['window_start_time' => '8:00am'],
            'errors' => ['window_start_time' => __('The :attribute is not in the right format.', ['attribute' => __('start time')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window start time is after end time' => fn () => [
            'state' => [
                'window_start_time' => '11:00',
                'window_end_time' => '8:00',
            ],
            'errors' => [
                'window_start_time' => __('The :attribute must be before the :date.', ['attribute' => __('start time'), 'date' => __('end time')]),
                'window_end_time' => __('The :attribute must be after the :date.', ['attribute' => __('end time'), 'date' => __('start time')]),
            ],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window end time is missing' => fn () => [
            'state' => ['window_end_time' => null],
            'errors' => ['window_end_time' => __('You must enter a :attribute', ['attribute' => __('end time')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window end time is an invalid time' => fn () => [
            'state' => ['window_end_time' => '1200'],
            'errors' => ['window_end_time' => __('The :attribute is not in the right format.', ['attribute' => __('end time')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Timezone is missing' => fn () => [
            'state' => ['timezone' => null],
            'errors' => ['timezone' => __('You must enter a :attribute', ['attribute' => __('timezone')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Timezone is invalid' => fn () => [
            'state' => ['timezone' => 'my timezone'],
            'errors' => ['timezone' => __('You must enter a :attribute', ['attribute' => __('timezone')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Window flexibility is not a boolean value' => fn () => [
            'state' => ['window_flexibility' => ['false']],
            'errors' => ['window_flexibility' => __('validation.boolean', ['attribute' => __('window flexibility')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Weekday availabilities is not an array' => fn () => [
            'state' => ['weekday_availabilities' => Availability::Available->value],
            'errors' => [
                'weekday_availabilities' => __('validation.array', ['attribute' => __('availability')]),
                'weekday_availabilities.monday' => __('validation.required', ['attribute' => __('availability for Monday')]),
                'weekday_availabilities.tuesday' => __('validation.required', ['attribute' => __('availability for Tuesday')]),
                'weekday_availabilities.wednesday' => __('validation.required', ['attribute' => __('availability for Wednesday')]),
                'weekday_availabilities.thursday' => __('validation.required', ['attribute' => __('availability for Thursday')]),
                'weekday_availabilities.friday' => __('validation.required', ['attribute' => __('availability for Friday')]),
                'weekday_availabilities.saturday' => __('validation.required', ['attribute' => __('availability for Saturday')]),
                'weekday_availabilities.sunday' => __('validation.required', ['attribute' => __('availability for Sunday')]),
            ],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Weekday availabilities are invalid' => fn () => [
            'state' => [
                'weekday_availabilities.monday' => 'my availability',
                'weekday_availabilities.tuesday' => 'my availability',
                'weekday_availabilities.wednesday' => 'my availability',
                'weekday_availabilities.thursday' => 'my availability',
                'weekday_availabilities.friday' => 'my availability',
                'weekday_availabilities.saturday' => 'my availability',
                'weekday_availabilities.sunday' => 'my availability',
            ],
            'errors' => [
                'weekday_availabilities.monday' => __('validation.in', ['attribute' => __('availability for Monday')]),
                'weekday_availabilities.tuesday' => __('validation.in', ['attribute' => __('availability for Tuesday')]),
                'weekday_availabilities.wednesday' => __('validation.in', ['attribute' => __('availability for Wednesday')]),
                'weekday_availabilities.thursday' => __('validation.in', ['attribute' => __('availability for Thursday')]),
                'weekday_availabilities.friday' => __('validation.in', ['attribute' => __('availability for Friday')]),
                'weekday_availabilities.saturday' => __('validation.in', ['attribute' => __('availability for Saturday')]),
                'weekday_availabilities.sunday' => __('validation.in', ['attribute' => __('availability for Sunday')]),
            ],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Meeting types is missing' => fn () => [
            'state' => ['meeting_types' => null],
            'errors' => ['meeting_types' => __('You must select at least one way to attend the meeting.', ['attribute' => __('Ways to attend')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
            ],
        ],
        'Meeting types is not an array' => fn () => [
            'state' => ['meeting_types' => MeetingType::InPerson->value],
            'errors' => ['meeting_types' => __('validation.array', ['attribute' => __('Ways to attend')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
            ],
        ],
        'Meeting type is invalid' => fn () => [
            'state' => ['meeting_types' => ['my meeting']],
            'errors' => ['meeting_types.0' => __('You must select a valid meeting type.')],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
            ],
        ],
        'Street address is missing' => fn () => [
            'state' => ['street_address' => null],
            'errors' => ['street_address' => __('You must enter a :attribute for the meeting location.', ['attribute' => __('Street address')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Street address is invalid' => fn () => [
            'state' => ['street_address' => false],
            'errors' => ['street_address' => __('validation.string', ['attribute' => __('Street address')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Unit/suite/floor is invalid' => fn () => [
            'state' => ['unit_suite_floor' => false],
            'errors' => ['unit_suite_floor' => __('validation.string', ['attribute' => __('Unit, suite, or floor')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Locality is missing' => fn () => [
            'state' => ['locality' => null],
            'errors' => ['locality' => __('You must enter a :attribute for the meeting location.', ['attribute' => __('city or town')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Locality is invalid' => fn () => [
            'state' => ['locality' => false],
            'errors' => ['locality' => __('validation.string', ['attribute' => __('city or town')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Region is missing' => fn () => [
            'state' => ['region' => null],
            'errors' => ['region' => __('You must enter a :attribute for the meeting location.', ['attribute' => __('province or territory')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Region is invalid' => fn () => [
            'state' => ['region' => 'ZZ'],
            'errors' => ['region' => __('validation.in', ['attribute' => __('province or territory')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Postal code is missing' => fn () => [
            'state' => ['postal_code' => null],
            'errors' => ['postal_code' => __('You must enter a :attribute for the meeting location.', ['attribute' => __('Postal code')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Postal code is invalid' => fn () => [
            'state' => ['postal_code' => '123456'],
            'errors' => ['postal_code' => __('validation.postal_code')],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Directions is not an array' => fn () => [
            'state' => ['directions' => 'Use the front elevator to go to the second floor.'],
            'errors' => ['directions' => __('validation.array', ['attribute' => __('directions')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Meeting software is missing' => fn () => [
            'state' => ['meeting_software' => null],
            'errors' => ['meeting_software' => __('You must indicate the :attribute.', ['attribute' => __('meeting software')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::WebConference->value,
            ],
        ],
        'Meeting software is invalid' => fn () => [
            'state' => ['meeting_software' => false],
            'errors' => ['meeting_software' => __('validation.string', ['attribute' => __('meeting software')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::WebConference->value,
            ],
        ],
        'Alternative meeting software is invalid' => fn () => [
            'state' => ['alternative_meeting_software' => ['false']],
            'errors' => ['alternative_meeting_software' => __('validation.boolean', ['attribute' => __('alternative meeting software')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::WebConference->value,
            ],
        ],
        'Meeting url is missing' => fn () => [
            'state' => ['meeting_url' => null],
            'errors' => ['meeting_url' => __('You must enter a :attribute.', ['attribute' => __('link to join the meeting')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::WebConference->value,
            ],
        ],
        'Meeting url is invalid' => fn () => [
            'state' => ['meeting_url' => 'not_a_url'],
            'errors' => ['meeting_url' => __('validation.url', ['attribute' => __('link to join the meeting')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::WebConference->value,
            ],
        ],
        'Additional video information is not an array' => fn () => [
            'state' => ['additional_video_information' => 'more info'],
            'errors' => ['additional_video_information' => __('validation.array', ['attribute' => __('additional video information')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::WebConference->value,
            ],
        ],
        'Meeting phone number is missing' => fn () => [
            'state' => ['meeting_phone' => null],
            'errors' => ['meeting_phone' => __('validation.required', ['attribute' => __('phone number to join the meeting')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::Phone->value,
            ],
        ],
        'Meeting phone number is invalid' => fn () => [
            'state' => ['meeting_phone' => '1800123456'],
            'errors' => ['meeting_phone' => __('validation.phone', ['attribute' => __('phone number to join the meeting')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::Phone->value,
            ],
        ],
        'Additional phone information is not an array' => fn () => [
            'state' => ['additional_phone_information' => 'Press 1 after the beep.'],
            'errors' => ['additional_phone_information' => __('validation.array', ['attribute' => __('additional phone information')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::Phone->value,
            ],
        ],
        'Materials by date missing - interviews' => fn () => [
            'state' => ['materials_by_date' => null],
            'errors' => ['materials_by_date' => __('You must enter a :attribute.', ['attribute' => __('date for materials to be sent by')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Materials by date is invalid - interviews' => fn () => [
            'state' => ['materials_by_date' => 'someday'],
            'errors' => ['materials_by_date' => __('Please enter a valid :attribute.', ['attribute' => __('date for materials to be sent by')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Materials by date is after completed date - interviews' => fn () => [
            'state' => [
                'materials_by_date' => now()->addMonth(5),
                'complete_by_date' => now()->addMonth(4),
            ],
            'errors' => [
                'materials_by_date' => __('The :attribute must be before the :date.', ['attribute' => __('date for materials to be sent by'), 'date' => __('due date')]),
                'complete_by_date' => __('The :attribute must be after the :date.', ['attribute' => __('due date'), 'date' => __('date for materials to be sent by')]),
            ],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Materials by date missing - other-async' => fn () => [
            'state' => ['materials_by_date' => null],
            'errors' => ['materials_by_date' => __('You must enter a :attribute.', ['attribute' => __('date for materials to be sent by')])],
            'modifiers' => [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Materials by date is invalid - other-async' => fn () => [
            'state' => ['materials_by_date' => 'someday'],
            'errors' => ['materials_by_date' => __('Please enter a valid :attribute.', ['attribute' => __('date for materials to be sent by')])],
            'modifiers' => [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Materials by date is after completed date - other-async' => fn () => [
            'state' => [
                'materials_by_date' => now()->addMonth(5),
                'complete_by_date' => now()->addMonth(4),
            ],
            'errors' => [
                'materials_by_date' => __('The :attribute must be before the :date.', ['attribute' => __('date for materials to be sent by'), 'date' => __('due date')]),
                'complete_by_date' => __('The :attribute must be after the :date.', ['attribute' => __('due date'), 'date' => __('date for materials to be sent by')]),
            ],
            'modifiers' => [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Materials by date missing - survey' => fn () => [
            'state' => ['materials_by_date' => null],
            'errors' => ['materials_by_date' => __('You must enter a :attribute.', ['attribute' => __('date for materials to be sent by')])],
            'modifiers' => [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Materials by date is invalid - survey' => fn () => [
            'state' => ['materials_by_date' => 'someday'],
            'errors' => ['materials_by_date' => __('Please enter a valid :attribute.', ['attribute' => __('date for materials to be sent by')])],
            'modifiers' => [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Materials by date is after completed date - survey' => fn () => [
            'state' => [
                'materials_by_date' => now()->addMonth(5),
                'complete_by_date' => now()->addMonth(4),
            ],
            'errors' => [
                'materials_by_date' => __('The :attribute must be before the :date.', ['attribute' => __('date for materials to be sent by'), 'date' => __('due date')]),
                'complete_by_date' => __('The :attribute must be after the :date.', ['attribute' => __('due date'), 'date' => __('date for materials to be sent by')]),
            ],
            'modifiers' => [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Complete by date missing - interviews' => fn () => [
            'state' => ['complete_by_date' => null],
            'errors' => ['complete_by_date' => __('You must enter a :attribute.', ['attribute' => __('due date')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Complete by date is invalid - interviews' => fn () => [
            'state' => ['complete_by_date' => 'someday'],
            'errors' => ['complete_by_date' => __('Please enter a valid :attribute.', ['attribute' => __('due date')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Complete by date missing - other-async' => fn () => [
            'state' => ['complete_by_date' => null],
            'errors' => ['complete_by_date' => __('You must enter a :attribute.', ['attribute' => __('due date')])],
            'modifiers' => [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Complete by date is invalid - other-async' => fn () => [
            'state' => ['complete_by_date' => 'someday'],
            'errors' => ['complete_by_date' => __('Please enter a valid :attribute.', ['attribute' => __('due date')])],
            'modifiers' => [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Complete by date missing - survey' => fn () => [
            'state' => ['complete_by_date' => null],
            'errors' => ['complete_by_date' => __('You must enter a :attribute.', ['attribute' => __('due date')])],
            'modifiers' => [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Complete by date is invalid - survey' => fn () => [
            'state' => ['complete_by_date' => 'someday'],
            'errors' => ['complete_by_date' => __('Please enter a valid :attribute.', ['attribute' => __('due date')])],
            'modifiers' => [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Document languages missing - other-async' => fn () => [
            'state' => ['document_languages' => null],
            'errors' => ['document_languages' => __('Please select a language that the engagement documents will be in.')],
            'modifiers' => [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Document languages not an array - other-async' => fn () => [
            'state' => ['document_languages' => 'en'],
            'errors' => ['document_languages' => __('validation.array', ['attribute' => __('document languages')])],
            'modifiers' => [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Document language is invalid - other-async' => fn () => [
            'state' => ['document_languages' => ['xx']],
            'errors' => ['document_languages.0' => __('Please select a language that the engagement documents will be in.')],
            'modifiers' => [
                'format' => EngagementFormat::OtherAsync->value,
            ],
        ],
        'Document languages missing - survey' => fn () => [
            'state' => ['document_languages' => null],
            'errors' => ['document_languages' => __('Please select a language that the engagement documents will be in.')],
            'modifiers' => [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Document languages not an array - survey' => fn () => [
            'state' => ['document_languages' => 'en'],
            'errors' => ['document_languages' => __('validation.array', ['attribute' => __('document languages')])],
            'modifiers' => [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Document language is invalid - survey' => fn () => [
            'state' => ['document_languages' => ['xx']],
            'errors' => ['document_languages.0' => __('Please select a language that the engagement documents will be in.')],
            'modifiers' => [
                'format' => EngagementFormat::Survey->value,
            ],
        ],
        'Accepted formats missing' => fn () => [
            'state' => ['accepted_formats' => null],
            'errors' => ['other_accepted_formats' => __('You must indicate the :attribute.', ['attribute' => __('accepted formats')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['other_accepted_format'],
            ],
        ],
        'Accepted formats is not an array' => fn () => [
            'state' => ['accepted_formats' => AcceptedFormat::Writing->value],
            'errors' => ['accepted_formats' => __('validation.array', ['attribute' => __('accepted formats')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['other_accepted_format'],
            ],
        ],
        'Accepted format is invalid' => fn () => [
            'state' => ['accepted_formats' => ['Text']],
            'errors' => ['accepted_formats.0' => __('You must select a valid format.')],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['other_accepted_format'],
            ],
        ],
        'Other accepted formats is not a boolean' => fn () => [
            'state' => ['other_accepted_formats' => 'false'],
            'errors' => ['other_accepted_formats' => __('validation.boolean', ['attribute' => __('other accepted formats')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Other accepted format is not a string' => fn () => [
            'state' => [
                'other_accepted_formats' => true,
                'other_accepted_format' => ['en' => false],
            ],
            'errors' => ['other_accepted_format.en' => __('The other accepted format must be a string.')],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Other accepted format is missing required translation' => fn () => [
            'state' => [
                'other_accepted_formats' => true,
                'other_accepted_format' => ['es' => 'la escritura'],
            ],
            'errors' => [
                'other_accepted_format.en' => __('The other accepted format must be provided in either English or French.'),
                'other_accepted_format.fr' => __('The other accepted format must be provided in either English or French.'),
            ],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
                'without' => ['other_accepted_format.en'],
            ],
        ],
        'Open to other formats is not a boolean' => fn () => [
            'state' => ['open_to_other_formats' => 'ture'],
            'errors' => ['open_to_other_formats' => __('validation.boolean', ['attribute' => 'open to other formats'])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Paid is not a boolean' => fn () => [
            'state' => ['paid' => 'false'],
            'errors' => ['paid' => __('validation.boolean', ['attribute' => __('paid')])],
        ],
        'Signup by date is missing' => fn () => [
            'state' => ['signup_by_date' => null],
            'errors' => ['signup_by_date' => __('You must enter a :attribute.', ['attribute' => __('sign up deadline')])],
        ],
        'Signup by date is an invalid date' => fn () => [
            'state' => ['signup_by_date' => 'someday'],
            'errors' => ['signup_by_date' => __('Please enter a valid date for the :attribute.', ['attribute' => __('sign up deadline')])],
        ],
        'Signup by date after window start date' => fn () => [
            'state' => [
                'window_start_date' => now()->addMonth(1),
                'signup_by_date' => now()->addMonth(2),
            ],
            'errors' => ['signup_by_date' => __('The :attribute must be before the :date.', ['attribute' => __('sign up deadline'), 'date' => __('start date')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
        'Signup by date after materials by date' => fn () => [
            'state' => [
                'materials_by_date' => now()->addMonth(1),
                'signup_by_date' => now()->addMonth(2),
            ],
            'errors' => ['signup_by_date' => __('The :attribute must be before the :date.', ['attribute' => __('sign up deadline'), 'date' => __('date for materials to be sent by')])],
            'modifiers' => [
                'format' => EngagementFormat::Interviews->value,
                'meetingType' => MeetingType::InPerson->value,
            ],
        ],
    ];
});
