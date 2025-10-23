<?php

use App\Enums\IndividualRole;

dataset('individualIsReady', function () {

    $ready = true;
    $notReady = false;
    $participant = [IndividualRole::ConsultationParticipant->value];
    $connector = [IndividualRole::CommunityConnector->value];
    $consultant = [IndividualRole::AccessibilityConsultant->value];
    $allRoles = [
        IndividualRole::ConsultationParticipant->value,
        IndividualRole::AccessibilityConsultant->value,
        IndividualRole::CommunityConnector->value,
    ];

    return [
        'No roles set' => [
            [
                'oriented_at' => null,
            ],
            [
            ],
            $notReady,
        ],
        'Participant: not approved' => [
            [
                'oriented_at' => null,
            ],
            [
                'roles' => $participant,
            ],
            $notReady,
        ],
        'Participant: approved' => [
            [],
            [
                'roles' => $participant,
            ],
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
            $notReady,
        ],
        'Connector: approved; draft' => [
            [],
            [
                'roles' => $connector,
                'published_at' => null,
            ],
            $notReady,
        ],
        'Connector: approved; published' => [
            [],
            [
                'roles' => $connector,
            ],
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
            $notReady,
        ],
        'Consultant: approved; draft' => [
            [],
            [
                'roles' => $consultant,
                'published_at' => null,
            ],
            $notReady,
        ],
        'Consultant: approved; published' => [
            [],
            [
                'roles' => $consultant,
            ],
            $ready,
        ],
        'All roles: not approved' => [
            [
                'oriented_at' => null,
            ],
            [
                'roles' => $allRoles,
            ],
            $notReady,
        ],
        'All roles: approved; draft' => [
            [],
            [
                'roles' => $allRoles,
                'published_at' => null,
            ],
            $notReady,
        ],
        'All roles: approved; published' => [
            [],
            [
                'roles' => $allRoles,
            ],
            $ready,
        ],
    ];
});
