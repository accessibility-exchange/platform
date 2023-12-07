<?php

dataset('removeNotificaitonableRequestValidationErrors', function () {
    return [
        'missing notificationable type' => [
            ['notificationable_type' => null],
            fn () => ['notificationable_type' => __('validation.required', ['attribute' => __('notificationable type')])],
        ],
        'notificationable type not a string' => [
            ['notificationable_type' => false],
            fn () => [
                'notificationable_type' => __('validation.string', ['attribute' => __('notificationable type')]),
                'notificationable_type' => __('validation.in', ['attribute' => __('notificationable type')]),
            ],
        ],
        'notificationable type is invalid' => [
            ['notificationable_type' => 'fakeClass'],
            fn () => ['notificationable_type' => __('validation.in', ['attribute' => __('notificationable type')])],
        ],
        'missing notificationable id' => [
            ['notificationable_id' => null],
            fn () => ['notificationable_id' => __('validation.required', ['attribute' => __('notificationable id')])],
        ],
        'notificationable id not an integer' => [
            ['notificationable_id' => 'test'],
            fn () => [
                'notificationable_id' => __('validation.integer', ['attribute' => __('notificationable id')]),
            ],
        ],
        'notificationable id is invalid' => [
            ['notificationable_id' => 10000000],
            fn () => [
                'notificationable_id' => __('validation.in', ['attribute' => __('notificationable id')]),
            ],
        ],
    ];
});
