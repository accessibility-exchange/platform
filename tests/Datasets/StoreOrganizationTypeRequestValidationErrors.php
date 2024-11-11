<?php

dataset('storeOrganizationTypeRequestValidationErrors', function () {
    return array_map('array_values', [
        'Type is missing' => [
            'state' => ['type' => null],
            'errors' => fn () => ['type' => __('You must select what type of organization you are.')],
        ],
        'Type is invalid' => [
            'state' => ['type' => 'other'],
            'errors' => fn () => ['type' => __('validation.exists', ['attribute' => __('organization type')])],
        ],
    ]);
});
