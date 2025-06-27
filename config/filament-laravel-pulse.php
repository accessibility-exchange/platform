<?php

declare(strict_types=1);

return [
    'components' => [
        'cache' => [
            'columnSpan' => [
                'sm' => 12,
                'lg' => 6,
                'xl' => 5,
            ],
            'rows' => 2,
            'cols' => 'full',
            'ignoreAfter' => '1 day',
            'isDiscovered' => true,
            'isLazy' => true,
            'sort' => null,
            'canView' => true,
            'columnStart' => [],
        ],

        'exceptions' => [
            'columnSpan' => [
                'sm' => 12,
                'lg' => 6,
                'xl' => 7,
            ],
            'rows' => 2,
            'cols' => 'full',
            'ignoreAfter' => '1 day',
            'isDiscovered' => true,
            'isLazy' => true,
            'sort' => null,
            'canView' => true,
            'columnStart' => [],
        ],

        'queues' => [
            'columnSpan' => [
                'sm' => 12,
                'lg' => 6,
                'xl' => 7,
            ],
            'cols' => 'full',
            'ignoreAfter' => '1 day',
            'isDiscovered' => true,
            'isLazy' => true,
            'sort' => null,
            'canView' => true,
            'columnStart' => [],
        ],

        'servers' => [
            'columnSpan' => [
                'md' => 12,
                'xl' => 12,
            ],
            'cols' => 'full',
            'ignoreAfter' => '1 day',
            'isDiscovered' => true,
            'isLazy' => true,
            'sort' => null,
            'canView' => true,
            'columnStart' => [],
        ],

        'slow-out-going-requests' => [
            'columnSpan' => 'full',
            'cols' => 'full',
            'ignoreAfter' => '1 day',
            'isDiscovered' => true,
            'isLazy' => true,
            'sort' => null,
            'canView' => true,
            'columnStart' => [],
        ],

        'slow-queries' => [
            'columnSpan' => [
                'md' => 12,
                'xl' => 12,
            ],
            'cols' => 'full',
            'ignoreAfter' => '1 day',
            'isDiscovered' => true,
            'isLazy' => true,
            'sort' => null,
            'canView' => true,
            'columnStart' => [],
        ],

        'slow-requests' => [
            'columnSpan' => [
                'md' => 12,
                'xl' => 12,
            ],
            'cols' => 'full',
            'ignoreAfter' => '1 day',
            'isDiscovered' => true,
            'isLazy' => true,
            'sort' => null,
            'canView' => true,
            'columnStart' => [],
        ],

        'usage' => [
            'columnSpan' => [
                'sm' => 12,
                'lg' => 6,
                'xl' => 5,
            ],
            'rows' => 2,
            'cols' => 'full',
            'ignoreAfter' => '1 day',
            'isDiscovered' => true,
            'isLazy' => true,
            'sort' => null,
            'canView' => true,
            'columnStart' => [],
        ],

        'slow-jobs' => [
            'columnSpan' => [
                'md' => 5,
                'xl' => 5,
            ],
            'rows' => 2,
            'cols' => 'full',
            'ignoreAfter' => '1 day',
            'isDiscovered' => true,
            'isLazy' => true,
            'sort' => null,
            'canView' => true,
            'columnStart' => [],
        ],
    ],
];
