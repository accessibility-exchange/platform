<?php

use App\Enums\IndividualRole;

dataset('individualIsReady', function () {

    $ready = true;
    $notReady = false;
    $add_payment_types = true;
    $dont_add_payment_types = false;
    $participant = [IndividualRole::ConsultationParticipant->value];
    $connector = [IndividualRole::CommunityConnector->value];
    $consultant = [IndividualRole::AccessibilityConsultant->value];
    $allRoles = [
        IndividualRole::ConsultationParticipant->value,
        IndividualRole::AccessibilityConsultant->value,
        IndividualRole::CommunityConnector->value,
    ];

    return [
        'Participant: not approved' => [
            [
                'oriented_at' => null,
            ],
            [
                'roles' => $participant,
                'other_payment_type' => null,
            ],
            $dont_add_payment_types,
            $notReady,
        ],
        'Participant: approved; no payment types; no other_payment_type' => [
            [],
            [
                'roles' => $participant,
                'other_payment_type' => null,
            ],
            $dont_add_payment_types,
            $notReady,
        ],
        'Participant: approved; no payment types; other payment type' => [
            [],
            [
                'roles' => $participant,
                'other_payment_type' => 'money order',
            ],
            $dont_add_payment_types,
            $ready,
        ],
        'Participant: approved; payment types; no other payment type' => [
            [],
            [
                'roles' => $participant,
                'other_payment_type' => null,
            ],
            $add_payment_types,
            $ready,
        ],
        'Participant: approved; payment types; other payment type' => [
            [],
            [
                'roles' => $participant,
                'other_payment_type' => 'money order',
            ],
            $add_payment_types,
            $ready,
        ],
        'Connector: not approved' => [
            [
                'oriented_at' => null,
            ],
            [
                'roles' => $connector,
                'published_at' => null,
            ],
            $dont_add_payment_types,
            $notReady,
        ],
        'Connector: approved; draft' => [
            [],
            [
                'roles' => $connector,
                'published_at' => null,
            ],
            $dont_add_payment_types,
            $notReady,
        ],
        'Connector: approved; published' => [
            [],
            [
                'roles' => $connector,
            ],
            $dont_add_payment_types,
            $ready,
        ],
        'Consultant: not approved' => [
            [
                'oriented_at' => null,
            ],
            [
                'roles' => $consultant,
                'published_at' => null,
            ],
            $dont_add_payment_types,
            $notReady,
        ],
        'Consultant: approved; draft' => [
            [],
            [
                'roles' => $consultant,
                'published_at' => null,
            ],
            $dont_add_payment_types,
            $notReady,
        ],
        'Consultant: approved; published' => [
            [],
            [
                'roles' => $consultant,
            ],
            $dont_add_payment_types,
            $ready,
        ],
        'All roles: not approved' => [
            [
                'oriented_at' => null,
            ],
            [
                'roles' => $allRoles,
                'other_payment_type' => null,
            ],
            $dont_add_payment_types,
            $notReady,
        ],
        'All roles: approved; no payment types; no other_payment_type; draft' => [
            [],
            [
                'roles' => $allRoles,
                'other_payment_type' => null,
                'published_at' => null,
            ],
            $dont_add_payment_types,
            $notReady,
        ],
        'All roles: approved; no payment types; no other_payment_type; published' => [
            [],
            [
                'roles' => $allRoles,
                'other_payment_type' => null,
            ],
            $dont_add_payment_types,
            $notReady,
        ],
        'All roles: approved; no payment types; other payment type; draft' => [
            [],
            [
                'roles' => $allRoles,
                'other_payment_type' => 'money order',
                'published_at' => null,
            ],
            $dont_add_payment_types,
            $notReady,
        ],
        'All roles: approved; no payment types; other payment type; published' => [
            [],
            [
                'roles' => $allRoles,
                'other_payment_type' => 'money order',
            ],
            $dont_add_payment_types,
            $ready,
        ],
        'All roles: approved; payment types; no other payment type; draft' => [
            [],
            [
                'roles' => $allRoles,
                'other_payment_type' => null,
                'published_at' => null,
            ],
            $add_payment_types,
            $notReady,
        ],
        'All roles: approved; payment types; no other payment type; published' => [
            [],
            [
                'roles' => $allRoles,
                'other_payment_type' => null,
            ],
            $add_payment_types,
            $ready,
        ],
        'All roles: approved; payment types; other payment type; draft' => [
            [],
            [
                'roles' => $allRoles,
                'other_payment_type' => 'money order',
                'published_at' => null,
            ],
            $add_payment_types,
            $notReady,
        ],
        'All roles: approved; payment types; other payment type; published' => [
            [],
            [
                'roles' => $allRoles,
                'other_payment_type' => 'money order',
            ],
            $add_payment_types,
            $ready,
        ],
    ];
});
