<?php

use App\Enums\IndividualRole;

dataset('saveIndividualRolesRequestValidationErrors', function () {
    return [
        'Roles missing' => [
            [],
            fn () => ['roles' => __('You must select what you would like to do on the website.')],
        ],
        'Roles not an array' => [
            fn () => ['roles' => IndividualRole::AccessibilityConsultant->value],
            fn () => ['roles' => __('You must select what you would like to do on the website.')],
        ],
        'Role invalid' => [
            fn () => ['roles' => ['invalid-role']],
            fn () => ['roles.0' => __('You must select a valid role to perform on the website.')],
        ],
    ];
});
