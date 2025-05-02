<?php

return [
    'providers' => [
        'users' => [
            'driver' => 'encryptedUserProvider',
            'model' => App\Models\User::class,
            'table' => 'users',
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
];
