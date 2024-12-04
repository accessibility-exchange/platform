<?php

dataset('storeRegulatedOrganizationTypeRequestValidationErrors', function () {
    return [
        'Type is missing' => [
            'state' => ['type' => null],
            'errors' => fn () => ['type' => __('You must select what type of organization you are.')],
        ],
        'Type is not a string' => [
            'state' => ['type' => false],
            'errors' => fn () => ['type' => __('validation.string', ['attribute' => __('organization type')])],
        ],
        'Type is invalid' => [
            'state' => ['type' => 'other'],
            'errors' => fn () => ['type' => __('validation.exists', ['attribute' => __('organization type')])],
        ],
    ];
});
