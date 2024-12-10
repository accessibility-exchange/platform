<?php

dataset('destroyProjectRequestValidationErrors', function () {
    return [
        'Current password is missing' => fn () => [
            'state' => [],
            'errors' => ['current_password' => __('validation.required', ['attribute' => __('current password')])],
        ],
        'Current password is not a string' => fn () => [
            'state' => ['current_password' => false],
            'errors' => ['current_password' => __('validation.string', ['attribute' => __('current password')])],
        ],
        'Current password is not valid' => fn () => [
            'state' => ['current_password' => 'WrongPassword'],
            'errors' => ['current_password' => __('The provided password does not match your current password.')],
        ],
    ];
});
