<?php

dataset('addNotificaitonableRequestValidationErrors', function () {
    return [
        'missing notificationable type' => [
            ['notificationable_type' => null],
        ],
        'notificationable type not a string' => [
            ['notificationable_type' => false],
        ],
        'notificationable type is invalid' => [
            [],
            fn () => ['notificationable_type' => __('validation.in', ['attribute' => 'notificationable type'])],
        ],
        'missing notificationable id' => [
            ['notificationable_id' => null],
        ],
        'notificationable id not an integer' => [
            ['notificationable_id' => 'test'],
        ],
        'notificationable id is invalid' => [
            ['notificationable_id' => 10000000],
        ],
    ];
});
