<?php

dataset('storeRegulatedOrganizationLanguagesRequestValidationErrors', function () {
    return [
        'Languages is missing' => fn () => [
            'state' => ['languages' => null],
            'errors' => ['languages' => __('validation.required', ['attribute' => __('languages')])],
        ],
        'Languages is not an array' => fn () => [
            'state' => ['languages' => false],
            'errors' => ['languages' => __('validation.array', ['attribute' => __('languages')])],
        ],
    ];
});
