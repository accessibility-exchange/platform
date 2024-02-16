<?php

use App\Enums\ContactPerson;
use App\Models\User;

dataset('updateIndividualCommunicationAndConsultationPreferencesRequestValidationErrors', function () {
    return [
        'Preferred contact person is missing' => [
            'state' => ['preferred_contact_person' => null],
            'errors' => fn () => ['preferred_contact_person' => __('validation.required', ['attribute' => __('Preferred contact person')])],
        ],
        'Preferred contact person is invalid' => [
            'state' => ['preferred_contact_person' => 'other'],
            'errors' => fn () => ['preferred_contact_person' => __('validation.exists', ['attribute' => __('Preferred contact person')])],
        ],
        'Email is missing' => [
            'state' => [
                'email' => null,
                'preferred_contact_person' => ContactPerson::Me->value,
                'preferred_contact_method' => 'email',
            ],
            'errors' => fn () => ['email' => __('validation.required', ['attribute' => __('email address')])],
        ],
        'Email is invalid' => [
            'state' => ['email' => 'fake.com'],
            'errors' => fn () => ['email' => __('validation.email', ['attribute' => __('email address')])],
        ],
        'Email is not unique' => [
            'state' => fn () => ['email' => User::factory()->create()->email],
            'errors' => fn () => ['email' => __('A user with this email already exists.')],
        ],
        'Phone is missing if VRS specified' => [
            'state' => [
                'phone' => null,
                'vrs' => true,
            ],
            'errors' => fn () => ['phone' => __('Since you have indicated that your contact person needs VRS, please enter a phone number.')],
        ],
        'Phone is missing if preferred contact method' => [
            'state' => [
                'phone' => null,
                'preferred_contact_method' => 'phone',
            ],
            'errors' => fn () => ['phone' => __('validation.required', ['attribute' => __('phone number')])],
        ],
        'Phone is invalid' => [
            'state' => ['phone' => '123456789'],
            'errors' => fn () => ['phone' => __('validation.phone', ['attribute' => __('phone number')])],
        ],
        'VRS is not boolean' => [
            'state' => ['vrs' => 123],
            'errors' => fn () => ['vrs' => __('validation.boolean', ['attribute' => __('I require Video Relay Service (VRS) for phone calls')])],
        ],
        'Support person name is missing' => [
            'state' => [
                'support_person_name' => null,
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
            ],
            'errors' => fn () => ['support_person_name' => __('Your support person’s name is required if they are your preferred contact person.')],
        ],
        'Support person name is not a string' => [
            'state' => ['support_person_name' => false],
            'errors' => fn () => ['support_person_name' => __('validation.string', ['attribute' => __('support person’s name')])],
        ],
        'Support person email is missing' => [
            'state' => [
                'support_person_email' => null,
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
                'preferred_contact_method' => 'email',
            ],
            'errors' => fn () => ['support_person_email' => __('validation.required', ['attribute' => __('support person’s email')])],
        ],
        'Support person email is invalid' => [
            'state' => ['support_person_email' => 'fake.com'],
            'errors' => fn () => ['support_person_email' => __('validation.email', ['attribute' => __('support person’s email')])],
        ],
        'Support person phone is missing if VRS specified' => [
            'state' => [
                'support_person_phone' => null,
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
                'support_person_vrs' => true,
            ],
            'errors' => fn () => ['support_person_phone' => __('Since you have indicated that your support person needs VRS, please enter a phone number.')],
        ],
        'Support person phone is missing if preferred contact method' => [
            'state' => [
                'support_person_phone' => null,
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
                'preferred_contact_method' => 'phone',
            ],
            'errors' => fn () => ['support_person_phone' => __('validation.required', ['attribute' => __('support person’s phone number')])],
        ],
        'Support person phone is invalid' => [
            'state' => ['support_person_phone' => '123456789'],
            'errors' => fn () => ['support_person_phone' => __('validation.phone', ['attribute' => __('support person’s phone number')])],
        ],
        'Support person VRS is not boolean' => [
            'state' => [
                'support_person_vrs' => 123,
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
            ],
            'errors' => fn () => ['support_person_vrs' => __('validation.boolean', ['attribute' => __('support person requires Video Relay Service (VRS) for phone calls')])],
        ],
        'Preferred contact method is missing' => [
            'state' => ['preferred_contact_method' => null],
            'errors' => fn () => ['preferred_contact_method' => __('validation.required', ['attribute' => __('Preferred contact method')])],
        ],
        'Preferred contact method is invalid' => [
            'state' => ['preferred_contact_method' => 'other'],
            'errors' => fn () => ['preferred_contact_method' => __('validation.exists', ['attribute' => __('Preferred contact method')])],
        ],
        'Meeting types is missing' => [
            'state' => ['meeting_types' => null],
            'errors' => fn () => ['meeting_types' => __('validation.required', ['attribute' => __('Ways to attend')])],
        ],
        'Meeting types is not an array' => [
            'state' => ['meeting_types' => false],
            'errors' => fn () => ['meeting_types' => __('validation.array', ['attribute' => __('Ways to attend')])],
        ],
        'Meeting type is invalid' => [
            'state' => ['meeting_types' => ['other']],
            'errors' => fn () => ['meeting_types.0' => __('validation.exists', ['attribute' => __('Ways to attend')])],
        ],
    ];
});
