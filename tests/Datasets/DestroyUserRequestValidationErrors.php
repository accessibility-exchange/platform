<?php

use App\Enums\UserContext;

dataset('destroyUserRequestValidationErrors', function () {
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
            'errors' => ['current_password' => __('auth.wrong_password')],
        ],
        'Is only administrator of organization' => fn () => [
            'state' => ['current_password' => 'password'],
            'errors' => ['organizations'],
            'context' => UserContext::Organization->value,
        ],
        'Is only administrator of regulated organization' => fn () => [
            'state' => ['current_password' => 'password'],
            'errors' => ['organizations'],
            'context' => UserContext::RegulatedOrganization->value,
        ],
    ];
});
