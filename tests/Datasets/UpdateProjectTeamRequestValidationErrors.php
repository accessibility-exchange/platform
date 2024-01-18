<?php

dataset('updateProjectTeamRequestValidationErrors', function () {
    return [
        'Team size is not an array' => [
            'state' => ['team_size' => 'test'],
            'errors' => fn () => ['team_size' => __('validation.array', ['attribute' => __('team size')])],
        ],
        'Team size translation is not a string' => [
            'state' => ['team_size.en' => false],
            'errors' => fn () => ['team_size.en' => __('validation.string', ['attribute' => __('team size')])],
        ],
        'Team has disability of deaf lived experience is not a boolean' => [
            'state' => ['team_has_disability_or_deaf_lived_experience' => 123],
            'errors' => fn () => ['team_has_disability_or_deaf_lived_experience' => __('validation.boolean', ['attribute' => __('Our team has people with lived and living experiences of disability or being Deaf.')])],
        ],
        'Team trainings name is missing' => [
            'state' => ['team_trainings.0.name' => null],
            'errors' => fn () => ['team_trainings.0.name' => __('validation.required', ['attribute' => __('training name')])],
        ],
        'Team trainings date is missing' => [
            'state' => ['team_trainings.0.date' => null],
            'errors' => fn () => ['team_trainings.0.date' => __('validation.required', ['attribute' => __('training date')])],
        ],
        'Team trainings trainer name is missing' => [
            'state' => ['team_trainings.0.trainer_name' => null],
            'errors' => fn () => ['team_trainings.0.trainer_name' => __('validation.required', ['attribute' => __('training organization or trainer name')])],
        ],
        'Team trainings trainer url is missing' => [
            'state' => ['team_trainings.0.trainer_url' => null],
            'errors' => fn () => ['team_trainings.0.trainer_url' => __('validation.required', ['attribute' => __('training organization or trainer website address')])],
        ],
        'Contact person person name is missing' => [
            'state' => ['contact_person_name' => null],
            'errors' => fn () => ['contact_person_name' => __('validation.required', ['attribute' => __('Contact person')])],
        ],
        'Contact person email and phone number are missing' => [
            'state' => ['contact_person_email' => null, 'contact_person_phone' => null],
            'errors' => fn () => [
                'contact_person_email' => __('validation.required_without', ['attribute' => __('Contact person’s email'), 'values' => __('Contact person’s phone number')]),
                'contact_person_phone' => __('validation.required_without', ['attribute' => __('Contact person’s phone number'), 'values' => __('Contact person’s email')]),
            ],
        ],
        'Contact person email is missing when preferred contact is email' => [
            'state' => ['contact_person_email' => null, 'preferred_contact_method' => 'email'],
            'errors' => fn () => ['contact_person_email' => __('validation.required_if', ['attribute' => __('Contact person’s email'), 'other' => __('preferred contact method'), 'value' => __('email')])],
        ],
        'Contact person phone number is missing when preferred contact is phone' => [
            'state' => ['contact_person_phone' => null, 'preferred_contact_method' => 'phone'],
            'errors' => fn () => ['contact_person_phone' => __('validation.required_if', ['attribute' => __('Contact person’s phone number'), 'other' => __('preferred contact method'), 'value' => __('phone')])],
        ],
        'Preferred contact method is invalid' => [
            'state' => ['preferred_contact_method' => 'text'],
            'errors' => fn () => ['preferred_contact_method' => __('validation.exists', ['attribute' => __('preferred contact method')])],
        ],
        'Contact person vrs is not a boolean' => [
            'state' => ['contact_person_vrs' => 123],
            'errors' => fn () => ['contact_person_vrs' => __('validation.boolean', ['attribute' => __('Contact person requires Video Relay Service (VRS) for phone calls')])],
        ],
        'Contact person has vrs but no phone number' => [
            'state' => ['contact_person_vrs' => true],
            'errors' => fn () => ['contact_person_phone' => __('Since you have indicated that your contact person needs VRS, please enter a phone number.')],
            'without' => ['contact_person_phone'],
        ],
        'Contact person response time is missing' => [
            'state' => ['contact_person_response_time' => null],
            'errors' => fn () => [
                'contact_person_response_time.en' => __('An approximate response time must be provided in at least one language.'),
                'contact_person_response_time.fr' => __('An approximate response time must be provided in at least one language.'),
            ],
        ],
        'Contact person response time is missing required translation' => [
            'state' => ['contact_person_response_time' => ['es' => 'Tiempo de respuesta de la persona de contacto']],
            'errors' => fn () => [
                'contact_person_response_time.en' => __('An approximate response time must be provided in at least one language.'),
                'contact_person_response_time.fr' => __('An approximate response time must be provided in at least one language.'),
            ],
            'without' => ['contact_person_response_time'],
        ],
    ];
});
