<?php

dataset('updateIndividualInterestsRequestValidationErrors', function () {
    return [
        'Sectors is not an array' => fn () => [
            'state' => ['sectors' => 123],
            'errors' => ['sectors' => __('validation.array', ['attribute' => __('Regulated Organization type')])],
        ],
        'Sector is invalid' => fn () => [
            'state' => ['sectors' => [100000]],
            'errors' => ['sectors.0' => __('validation.exists', ['attribute' => __('Regulated Organization type')])],
        ],
        'Impacts is not an array' => fn () => [
            'state' => ['impacts' => 123],
            'errors' => ['impacts' => __('validation.array', ['attribute' => __('area of accessibility planning and design')])],
        ],
        'Impacts is invalid' => fn () => [
            'state' => ['impacts' => [100000]],
            'errors' => ['impacts.0' => __('validation.exists', ['attribute' => __('area of accessibility planning and design')])],
        ],
    ];
});
