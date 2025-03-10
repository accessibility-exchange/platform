<?php

dataset('storeRegulatedOrganizationTypeRequestValidationErrors', function () {
    return [
        'Type is missing' => fn () => [
            'state' => ['type' => null],
            'errors' => ['type' => __('You must select what type of organization you are.')],
        ],
        'Type is not a string' => fn () => [
            'state' => ['type' => false],
            'errors' => ['type' => __('validation.string', ['attribute' => __('organization type')])],
        ],
        'Type is invalid' => fn () => [
            'state' => ['type' => 'other'],
            'errors' => ['type' => __('validation.exists', ['attribute' => __('organization type')])],
        ],
    ];
});
