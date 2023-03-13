<?php

use App\Enums\ContactPerson;

dataset('accessNeedsFacilitationNotification', function () {
    return [
        'phone only' => [
            [
                'preferred_contact_person' => ContactPerson::Me->value,
                'preferred_contact_method' => 'phone',
                'email' => null,
            ],
        ],
        'phone only requires VRS' => [
            [
                'preferred_contact_person' => ContactPerson::Me->value,
                'preferred_contact_method' => 'phone',
                'email' => null,
                'vrs' => 1,
            ],
        ],
        'email only' => [
            [
                'preferred_contact_person' => ContactPerson::Me->value,
                'preferred_contact_method' => 'email',
                'phone' => null,
            ],
        ],
        'email and phone (preferred)' => [
            [
                'preferred_contact_person' => ContactPerson::Me->value,
                'preferred_contact_method' => 'phone',
            ],
        ],
        'email (preferred) and phone' => [
            [
                'preferred_contact_person' => ContactPerson::Me->value,
                'preferred_contact_method' => 'email',
            ],
        ],
        'support-person phone only' => [
            [
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
                'preferred_contact_method' => 'phone',
                'support_person_email' => null,
            ],
        ],
        'support-person phone only requires VRS' => [
            [
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
                'preferred_contact_method' => 'phone',
                'support_person_email' => null,
                'support_person_vrs' => 1,
            ],
        ],
        'support-person email only' => [
            [
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
                'preferred_contact_method' => 'email',
                'support_person_email' => null,
            ],
        ],
        'support-person email and phone (preferred)' => [
            [
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
                'preferred_contact_method' => 'phone',
            ],
        ],
        'support-person email (preferred) and phone' => [
            [
                'preferred_contact_person' => ContactPerson::SupportPerson->value,
                'preferred_contact_method' => 'email',
            ],
        ],
    ];
});
