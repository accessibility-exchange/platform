<?php

return [
    'channels' => [
        'flare' => [
            'driver' => 'flare',
        ],

        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily', 'flare'],
            'ignore_exceptions' => false,
        ],
    ],
];
