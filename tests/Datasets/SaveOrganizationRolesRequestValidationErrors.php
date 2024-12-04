<?php

dataset('saveOrganizationRolesRequestValidationErrors', function () {
    return [
        'Roles is missing' => [
            'state' => ['roles' => null],
            'errors' => fn () => ['roles' => __('You must select a role for your organization.')],
        ],
        'Roles is not an array' => [
            'state' => ['roles' => false],
            'errors' => fn () => ['roles' => __('validation.array', ['attribute' => __('roles')])],
        ],
        'Role is invalid' => [
            'state' => ['roles' => ['other']],
            'errors' => fn () => ['roles.0' => __('validation.exists', ['attribute' => __('roles')])],
        ],
    ];
});
