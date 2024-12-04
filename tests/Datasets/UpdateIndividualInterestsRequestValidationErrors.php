<?php

dataset('updateIndividualInterestsRequestValidationErrors', function () {
    return [
        'Sectors is not an array' => [
            'state' => ['sectors' => 123],
            'errors' => fn () => ['sectors' => __('validation.array', ['attribute' => __('Regulated Organization type')])],
        ],
        'Sector is invalid' => [
            'state' => ['sectors' => [100000]],
            'errors' => fn () => ['sectors.0' => __('validation.exists', ['attribute' => __('Regulated Organization type')])],
        ],
        'Impacts is not an array' => [
            'state' => ['impacts' => 123],
            'errors' => fn () => ['impacts' => __('validation.array', ['attribute' => __('area of accessibility planning and design')])],
        ],
        'Impacts is invalid' => [
            'state' => ['impacts' => [100000]],
            'errors' => fn () => ['impacts.0' => __('validation.exists', ['attribute' => __('area of accessibility planning and design')])],
        ],
    ];
});
