<?php

dataset('updateOrganizationInterestsRequestValidationErrors', function () {
    return [
        'Impacts is not an array' => fn () => [
            'state' => ['impacts' => 123],
            'errors' => ['impacts' => __('validation.array', ['attribute' => __('area of accessibility planning and design')])],
        ],
        'Impact is invalid' => fn () => [
            'state' => ['impacts' => [1000000]],
            'errors' => ['impacts.0' => __('validation.exists', ['attribute' => __('area of accessibility planning and design')])],
        ],
        'Sectors is not an array' => fn () => [
            'state' => ['sectors' => 123],
            'errors' => ['sectors' => __('validation.array', ['attribute' => __('Regulated Organization type')])],
        ],
        'Sector is invalid' => fn () => [
            'state' => ['sectors' => [1000000]],
            'errors' => ['sectors.0' => __('validation.exists', ['attribute' => __('Regulated Organization type')])],
        ],
    ];
});
