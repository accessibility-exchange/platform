<?php

use App\Models\User;

return [
    'providers' => [
        'users' => [
            'driver' => 'encryptedUserProvider',
            'model' => User::class,
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
