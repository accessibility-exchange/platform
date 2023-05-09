<?php

use App\Enums\IndividualRole;
use App\Enums\UserContext;

dataset('userHasTasksToComplete', function () {
    $hasTasksToComplete = true;
    $noTasksToComplete = false;

    return [
        'user not yet approved' => [
            [
                'user' => [
                    'oriented_at' => null,
                ],
            ],
            $hasTasksToComplete,
        ],
        'user suspended' => [
            [
                'user' => [
                    'suspended_at' => now(),
                ],
            ],
            $noTasksToComplete,
        ],
        'user is platform admin' => [
            [
                'user' => [
                    'context' => UserContext::Administrator->value,
                ],
            ],
            $noTasksToComplete,
        ],
        'user is training participant' => [
            [
                'user' => [
                    'context' => UserContext::TrainingParticipant->value,
                ],
            ],
            $noTasksToComplete,
        ],
        'user is individual; individual is not ready' => [
            [
                'user' => [
                    'context' => UserContext::Individual->value,
                ],
                'individual' => [
                    'roles' => [IndividualRole::AccessibilityConsultant->value],
                    'published_at' => null,
                ],
            ],
            $hasTasksToComplete,
        ],
        'user is individual; individual is ready' => [
            [
                'user' => [
                    'context' => UserContext::Individual->value,
                ],
                'individual' => [
                    'roles' => [IndividualRole::AccessibilityConsultant->value],
                    'published_at' => now(),
                ],
            ],
            $noTasksToComplete,
        ],
        'user is organization; organization connection missing' => [
            [
                'user' => [
                    'context' => UserContext::Organization->value,
                ],
            ],
            $noTasksToComplete,
        ],
        'user is organization; organization is suspended' => [
            [
                'user' => [
                    'context' => UserContext::Organization->value,
                ],
                'org' => [
                    'suspended_at' => now(),
                    'published_at' => now(),
                ],
            ],
            $noTasksToComplete,
        ],
        'user is organization; user is not organization admin' => [
            [
                'user' => [
                    'context' => UserContext::Organization->value,
                ],
                'org' => [
                    'published_at' => now(),
                ],
                'orgRole' => 'member',
            ],
            $noTasksToComplete,
        ],
        'user is organization; organization page is published' => [
            [
                'user' => [
                    'context' => UserContext::Organization->value,
                ],
                'org' => [
                    'published_at' => now(),
                ],
            ],
            $noTasksToComplete,
        ],
        'user is organization; organization page is not published' => [
            [
                'user' => [
                    'context' => UserContext::Organization->value,
                ],
                'org' => [],
            ],
            $hasTasksToComplete,
        ],
        'user is regulated organization; regulated organization connection missing' => [
            [
                'user' => [
                    'context' => UserContext::RegulatedOrganization->value,
                ],
            ],
            $noTasksToComplete,
        ],
        'user is regulated organization; regulated organization is suspended' => [
            [
                'user' => [
                    'context' => UserContext::RegulatedOrganization->value,
                ],
                'org' => [
                    'suspended_at' => now(),
                    'published_at' => now(),
                ],
            ],
            $noTasksToComplete,
        ],
        'user is regulated organization; user is not regulated organization admin' => [
            [
                'user' => [
                    'context' => UserContext::RegulatedOrganization->value,
                ],
                'org' => [
                    'published_at' => now(),
                ],
                'orgRole' => 'member',
            ],
            $noTasksToComplete,
        ],
        'user is regulated organization; regulated organization page is published and has projects' => [
            [
                'user' => [
                    'context' => UserContext::RegulatedOrganization->value,
                ],
                'org' => [
                    'published_at' => now(),
                ],
                'withProject' => true,
            ],
            $noTasksToComplete,
        ],
        'user is regulated organization; regulated organization page is published without projects' => [
            [
                'user' => [
                    'context' => UserContext::RegulatedOrganization->value,
                ],
                'org' => [
                    'published_at' => now(),
                ],
            ],
            $hasTasksToComplete,
        ],
        'user is regulated organization; regulated organization page is not published and has projects' => [
            [
                'user' => [
                    'context' => UserContext::RegulatedOrganization->value,
                ],
                'org' => [],
                'withProject' => true,
            ],
            $hasTasksToComplete,
        ],
        'user is regulated organization; regulated organization page is not published without projects' => [
            [
                'user' => [
                    'context' => UserContext::RegulatedOrganization->value,
                ],
                'org' => [],
            ],
            $hasTasksToComplete,
        ],
    ];
});
