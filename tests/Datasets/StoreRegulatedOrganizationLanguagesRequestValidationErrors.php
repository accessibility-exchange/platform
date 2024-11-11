<?php

dataset('storeRegulatedOrganizationLanguagesRequestValidationErrors', function () {
    return array_map('array_values', [
        'Languages is missing' => [
            'state' => ['languages' => null],
            'errors' => fn () => ['languages' => __('validation.required', ['attribute' => __('languages')])],
        ],
        'Languages is not an array' => [
            'state' => ['languages' => false],
            'errors' => fn () => ['languages' => __('validation.array', ['attribute' => __('languages')])],
        ],
    ]);
});
