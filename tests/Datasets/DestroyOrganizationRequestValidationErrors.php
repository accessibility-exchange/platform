<?php

dataset('destroyOrganizationRequestValidationErrors', function () {
    return [
        'Current password is missing' => fn () => [
            'state' => ['current_password' => null],
            'errors' => ['current_password' => __('validation.required', ['attribute' => __('current password')])],
        ],
        'Current password is not a string' => fn () => [
            'state' => ['current_password' => false],
            'errors' => ['current_password' => __('validation.string', ['attribute' => __('current password')])],
        ],
        'Current password does not match' => fn () => [
            'state' => ['current_password' => 'fake_password'],
            'errors' => ['current_password' => __('validation.current_password', ['attribute' => __('current password')])],
        ],
    ];
});
