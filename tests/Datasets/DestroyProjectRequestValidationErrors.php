<?php

dataset('destroyProjectRequestValidationErrors', function () {
    return [
        'Current password is missing' => [
            'state' => [],
            'errors' => fn () => ['current_password' => __('validation.required', ['attribute' => __('current password')])],
        ],
        'Current password is not a string' => [
            'state' => ['current_password' => false],
            'errors' => fn () => ['current_password' => __('validation.string', ['attribute' => __('current password')])],
        ],
        'Current password is not valid' => [
            'state' => ['current_password' => 'WrongPassword'],
            'errors' => fn () => ['current_password' => __('The provided password does not match your current password.')],
        ],
    ];
});
