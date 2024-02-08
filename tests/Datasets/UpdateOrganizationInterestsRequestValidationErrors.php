<?php

dataset('updateOrganizationInterestsRequestValidationErrors', function () {
    return [
        'Impacts is not an array' => [
            'state' => ['impacts' => 123],
            'errors' => fn () => ['impacts' => __('validation.array', ['attribute' => __('area of accessibility planning and design')])],
        ],
        'Impact is invalid' => [
            'state' => ['impacts' => [1000000]],
            'errors' => fn () => ['impacts.0' => __('validation.exists', ['attribute' => __('area of accessibility planning and design')])],
        ],
        'Sectors is not an array' => [
            'state' => ['sectors' => 123],
            'errors' => fn () => ['sectors' => __('validation.array', ['attribute' => __('Regulated Organization type')])],
        ],
        'Sector is invalid' => [
            'state' => ['sectors' => [1000000]],
            'errors' => fn () => ['sectors.0' => __('validation.exists', ['attribute' => __('Regulated Organization type')])],
        ],
    ];
});
