<?php

use Illuminate\Support\Str;

dataset('updateCommunicationAndConsultationPreferencesRequestValidationErrors', function () {
    $faker = Faker\Factory::create();

    return [
        'Preferred Contact Person missing' => [
            ['preferred_contact_person' => null],
            fn () => ['preferred_contact_person' => __('validation.required', ['attribute' => __('Preferred contact person')])],
        ],
        'Preferred Contact Person is invalid' => [
            ['preferred_contact_person' => 'other'],
            fn () => ['preferred_contact_person' => __('validation.exists', ['attribute' => __('Preferred contact person')])],
        ],
        'Email is not a string' => [
            ['email' => ['other']],
            fn () => ['email' => __('validation.string', ['attribute' => __('email address')])],
        ],
        'Email is invalid' => [
            ['email' => 'not an email address'],
            fn () => ['email' => __('validation.email', ['attribute' => __('email address')])],
        ],
        'Email is too long' => [
            ['email' => Str::random(255).'@'.$faker->safeEmailDomain()],
            fn () => ['email' => __('validation.max.string', ['attribute' => __('email address'), 'max' => '255'])],
        ],
        'Email is not unique' => [
            ['email' => 'existing@example.com'],
            fn () => ['email' => __('A user with this email already exists.')],
        ],
        'Phone is missing when VRS is enabled' => [
            [
                'phone' => null,
                'vrs' => true,
            ],
            fn () => ['phone' => __('Since you have indicated that your contact person needs VRS, please enter a phone number.')],
        ],
        'Phone is invalid' => [
            ['phone' => 'invalid'],
            fn () => ['phone' => __('validation.phone', ['attribute' => __('phone number')])],
        ],
        'VRS is not boolean' => [
            ['vrs' => ['not boolean']],
            fn () => ['vrs' => __('validation.boolean', ['attribute' => __('I require Video Relay Service (VRS) for phone calls')])],
        ],
        'Support person name is missing' => [
            ['support_person_name' => null],
            fn () => ['support_person_name' => __('Your support person’s name is required if they are your preferred contact person.')],
        ],
        'Support person name is not a string' => [
            ['support_person_name' => ['name']],
            fn () => ['support_person_name' => __('validation.string', ['attribute' => __('support person’s name')])],
        ],
        'Support person email is not a string' => [
            ['support_person_email' => ['email']],
            fn () => ['support_person_email' => __('validation.string', ['attribute' => __('support person’s email')])],
        ],
        'Support person email is invalid' => [
            ['support_person_email' => 'not a valid email'],
            fn () => ['support_person_email' => __('validation.email', ['attribute' => __('support person’s email')])],
        ],
        'Support person email is too long' => [
            ['support_person_email' => Str::random(255).'@'.$faker->safeEmailDomain()],
            fn () => ['support_person_email' => __('validation.max.string', ['attribute' => __('support person’s email'), 'max' => '255'])],
        ],
        'Support person phone is missing when VRS is enabled' => [
            [
                'support_person_phone' => null,
                'support_person_vrs' => true,
            ],
            fn () => ['support_person_phone' => __('Since you have indicated that your support person needs VRS, please enter a phone number.')],
        ],
        'Support person phone is invalid' => [
            ['support_person_phone' => 'invalid'],
            fn () => ['support_person_phone' => __('validation.phone', ['attribute' => __('support person’s phone number')])],
        ],
        'Support person VRS is not boolean' => [
            ['support_person_vrs' => ['not boolean']],
            fn () => ['support_person_vrs' => __('validation.boolean', ['attribute' => __('support person requires Video Relay Service (VRS) for phone calls')])],
        ],
        'Preferred contact method is invalid' => [
            ['preferred_contact_method' => 'other'],
            fn () => ['preferred_contact_method' => __('validation.exists', ['attribute' => __('Preferred contact method')])],
        ],
        'Consulting methods is missing' => [
            ['consulting_methods' => null],
            fn () => ['consulting_methods' => __('validation.required', ['attribute' => __('consulting methods')])],
        ],
        'Consulting methods is invalid' => [
            ['consulting_methods' => ['invalid']],
            fn () => ['consulting_methods.0' => __('validation.in', ['attribute' => __('consulting methods')])],
        ],
        'Meeting types is invalid' => [
            ['meeting_types' => null],
            fn () => ['meeting_types.0' => __('validation.required', ['attribute' => __('Ways to attend')])],
        ],
        'Meeting types is invalid' => [
            ['meeting_types' => false],
            fn () => ['meeting_types.0' => __('validation.array', ['attribute' => __('Ways to attend')])],
        ],
        'Meeting types is invalid' => [
            ['meeting_types' => ['invalid']],
            fn () => ['meeting_types.0' => __('validation.in', ['attribute' => __('Ways to attend')])],
        ],
    ];
});
