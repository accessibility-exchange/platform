<?php

use App\Enums\ContactMethod;

dataset('updateOrganizationContactInformationRequestValidationErrors', function () {
    return [
        'Contact person name is missing' => fn () => [
            'state' => ['contact_person_name' => null],
            'errors' => ['contact_person_name' => __('validation.required', ['attribute' => __('Contact person')])],
        ],
        'Contact person name is not a string' => fn () => [
            'state' => ['contact_person_name' => false],
            'errors' => ['contact_person_name' => __('validation.string', ['attribute' => __('Contact person')])],
        ],
        'Contact person email and phone are missing' => fn () => [
            'state' => [
                'contact_person_email' => null,
                'contact_person_phone' => null,
            ],
            'errors' => [
                'contact_person_email' => __('validation.required_without', ['attribute' => __('email address'), 'values' => __('phone number')]),
                'contact_person_phone' => __('validation.required_without', ['attribute' => __('phone number'), 'values' => __('email address')]),
            ],
        ],
        'Contact person email is missing when preferred contact method' => fn () => [
            'state' => [
                'contact_person_email' => null,
                'preferred_contact_method' => ContactMethod::Email->value,
            ],
            'errors' => ['contact_person_email' => __('validation.required_if', ['attribute' => __('email address'), 'other' => __('preferred contact method'), 'value' => ContactMethod::Email->value])],
        ],
        'Contact person email is invalid' => fn () => [
            'state' => ['contact_person_email' => 'fake.com'],
            'errors' => ['contact_person_email' => __('validation.email', ['attribute' => __('email address')])],
        ],
        'Contact person phone is missing when preferred contact method' => fn () => [
            'state' => [
                'contact_person_phone' => null,
                'preferred_contact_method' => ContactMethod::Phone->value,
            ],
            'errors' => ['contact_person_phone' => __('validation.required_if', ['attribute' => __('phone number'), 'other' => __('preferred contact method'), 'value' => ContactMethod::Phone->value])],
        ],
        'Contact person phone is missing when vrs requested' => fn () => [
            'state' => [
                'contact_person_phone' => null,
                'contact_person_vrs' => true,
            ],
            'errors' => ['contact_person_phone' => __('Since you have indicated that your contact person needs VRS, please enter a phone number.')],
        ],
        'Contact person phone is invalid' => fn () => [
            'state' => ['contact_person_phone' => '1234567'],
            'errors' => ['contact_person_phone' => __('validation.phone', ['attribute' => __('phone number')])],
        ],
        'Contact person vrs is not a boolean' => fn () => [
            'state' => ['contact_person_vrs' => 123],
            'errors' => ['contact_person_vrs' => __('validation.boolean', ['attribute' => __('Contact person requires Video Relay Service (VRS) for phone calls')])],
        ],
        'Preferred contact method is missing' => fn () => [
            'state' => ['preferred_contact_method' => null],
            'errors' => ['preferred_contact_method' => __('validation.required', ['attribute' => __('preferred contact method')])],
        ],
        'Preferred contact method is invalid' => fn () => [
            'state' => ['preferred_contact_method' => 'other'],
            'errors' => ['preferred_contact_method' => __('validation.exists', ['attribute' => __('preferred contact method')])],
        ],
        'Preferred contact language is missing' => fn () => [
            'state' => ['preferred_contact_language' => null],
            'errors' => ['preferred_contact_language' => __('validation.required', ['attribute' => __('preferred contact language')])],
        ],
        'Preferred contact language is invalid' => fn () => [
            'state' => ['preferred_contact_language' => 'xx'],
            'errors' => ['preferred_contact_language' => __('validation.exists', ['attribute' => __('preferred contact language')])],
        ],
    ];
});
