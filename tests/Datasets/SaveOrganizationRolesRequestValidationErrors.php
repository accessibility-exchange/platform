<?php

dataset('saveOrganizationRolesRequestValidationErrors', function () {
    return [
        'Roles is missing' => fn () => [
            'state' => ['roles' => null],
            'errors' => ['roles' => __('You must select a role for your organization.')],
        ],
        'Roles is not an array' => fn () => [
            'state' => ['roles' => false],
            'errors' => ['roles' => __('validation.array', ['attribute' => __('roles')])],
        ],
        'Role is invalid' => fn () => [
            'state' => ['roles' => ['other']],
            'errors' => ['roles.0' => __('validation.exists', ['attribute' => __('roles')])],
        ],
    ];
});
