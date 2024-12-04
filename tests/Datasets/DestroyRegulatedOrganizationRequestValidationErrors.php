<?php

dataset('destroyRegulatedOrganizationRequestValidationErrors', function () {
    return [
        'Current password is missing' => [
            'state' => ['current_password' => null],
            'errors' => fn () => ['current_password' => __('validation.required', ['attribute' => __('current password')])],
        ],
        'Current password is not a string' => [
            'state' => ['current_password' => false],
            'errors' => fn () => ['current_password' => __('validation.string', ['attribute' => __('current password')])],
        ],
        'Current password does not match' => [
            'state' => ['current_password' => 'fake_password'],
            'errors' => fn () => ['current_password' => __('The provided password does not match your current password.')],
        ],
    ];
});
