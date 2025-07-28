<?php

use App\Enums\ContactMethod;
use App\Enums\ContactPerson;
use App\Models\User;

dataset('updateIndividualCommunicationAndConsultationPreferencesRequestValidationErrors', function () {
    return [
        'Preferred contact person is missing' => fn () => [
            'state' => ['preferred_contact_person' => null],
            'errors' => ['preferred_contact_person' => __('validation.required', ['attribute' => __('Preferred contact person')])],
        ],
        'Preferred contact person is invalid' => fn () => [
            'state' => ['preferred_contact_person' => 'other'],
            'errors' => ['preferred_contact_person' => __('validation.exists', ['attribute' => __('Preferred contact person')])],
        ],
        'Email is missing' => fn () => [
            'state' => [
                'email' => null,
                'preferred_contact_person' => ContactPerson::Me->value,
                'phone' => phone('416-555-5555', 'CA')->formatForCountry('CA'),
                'preferred_contact_method' => 'phone',
            ],
            'errors' => ['email' => __('validation.required', ['attribute' => __('email address')])],
        ],
        'Email is missing when preferred contact method' => fn () => [
            'state' => [
                'email' => null,
                'preferred_contact_person' => ContactPerson::Me->value,
                'preferred_contact_method' => ContactMethod::Email->value,
            ],
            'errors' => ['email' => __('validation.required', ['attribute' => __('email address')])],
        ],
        'Email is invalid' => fn () => [
            'state' => ['email' => 'fake.com'],
            'errors' => ['email' => __('validation.email', ['attribute' => __('email address')])],
        ],
        'Email is not unique' => fn () => [
            'state' => ['email' => User::factory()->create()->email],
            'errors' => ['email' => __('A user with this email already exists.')],
        ],
        'Phone is missing if VRS specified' => fn () => [
            'state' => [
                'phone' => null,
                'vrs' => true,
            ],
            'errors' => ['phone' => __('Since you have indicated that you need VRS, please enter a phone number.')],
        ],
        'Phone is missing if preferred contact method' => fn () => [
            'state' => [
                'phone' => null,
                'preferred_contact_method' => ContactMethod::Phone->value,
            ],
            'errors' => ['phone' => __('validation.required', ['attribute' => __('phone number')])],
        ],
        'Phone is invalid' => fn () => [
            'state' => ['phone' => '123456789'],
            'errors' => ['phone' => __('validation.phone', ['attribute' => __('phone number')])],
        ],
        'VRS is not boolean' => fn () => [
            'state' => ['vrs' => 123],
            'errors' => ['vrs' => __('validation.boolean', ['attribute' => __('I require Video Relay Service (VRS) for phone calls')])],
        ],
        'Support person name is missing' => fn () => [
            'state' => [
                'support_person_name' => null,
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
            ],
            'errors' => ['support_person_name' => __('Your support person’s name is required if they are your preferred contact person.')],
        ],
        'Support person name is not a string' => fn () => [
            'state' => ['support_person_name' => false],
            'errors' => ['support_person_name' => __('validation.string', ['attribute' => __('support person’s name')])],
        ],
        'Support person email is missing' => fn () => [
            'state' => [
                'support_person_email' => null,
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
                'preferred_contact_method' => ContactMethod::Email->value,
            ],
            'errors' => ['support_person_email' => __('validation.required', ['attribute' => __('support person’s email')])],
        ],
        'Support person email is invalid' => fn () => [
            'state' => ['support_person_email' => 'fake.com'],
            'errors' => ['support_person_email' => __('validation.email', ['attribute' => __('support person’s email')])],
        ],
        'Support person phone is missing if VRS specified' => fn () => [
            'state' => [
                'support_person_phone' => null,
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
                'support_person_vrs' => true,
            ],
            'errors' => ['support_person_phone' => __('Since you have indicated that your support person needs VRS, please enter a phone number.')],
        ],
        'Support person phone is missing if preferred contact method' => fn () => [
            'state' => [
                'support_person_phone' => null,
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
                'preferred_contact_method' => ContactMethod::Phone->value,
            ],
            'errors' => ['support_person_phone' => __('validation.required', ['attribute' => __('support person’s phone number')])],
        ],
        'Support person phone is invalid' => fn () => [
            'state' => ['support_person_phone' => '123456789'],
            'errors' => ['support_person_phone' => __('validation.phone', ['attribute' => __('support person’s phone number')])],
        ],
        'Support person VRS is not boolean' => fn () => [
            'state' => [
                'support_person_vrs' => 123,
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
            ],
            'errors' => ['support_person_vrs' => __('validation.boolean', ['attribute' => __('support person requires Video Relay Service (VRS) for phone calls')])],
        ],
        'Preferred contact method is missing' => fn () => [
            'state' => ['preferred_contact_method' => null],
            'errors' => ['preferred_contact_method' => __('validation.required', ['attribute' => __('Preferred contact method')])],
        ],
        'Preferred contact method is invalid' => fn () => [
            'state' => ['preferred_contact_method' => 'other'],
            'errors' => ['preferred_contact_method' => __('validation.exists', ['attribute' => __('Preferred contact method')])],
        ],
        'Meeting types is missing' => fn () => [
            'state' => ['meeting_types' => null],
            'errors' => ['meeting_types' => __('validation.required', ['attribute' => __('Ways to attend')])],
        ],
        'Meeting types is not an array' => fn () => [
            'state' => ['meeting_types' => false],
            'errors' => ['meeting_types' => __('validation.array', ['attribute' => __('Ways to attend')])],
        ],
        'Meeting type is invalid' => fn () => [
            'state' => ['meeting_types' => ['other']],
            'errors' => ['meeting_types.0' => __('validation.exists', ['attribute' => __('Ways to attend')])],
        ],
    ];
});
