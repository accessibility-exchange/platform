<?php

dataset('storeOrganizationLanguagesRequestValidationErrors', function () {
    return [
        'Languages is missing' => fn () => [
            'state' => ['roles' => null],
            'errors' => ['languages' => __('validation.required', ['attribute' => __('languages')])],
        ],
        'Languages is not an array' => fn () => [
            'state' => ['languages' => false],
            'errors' => ['languages' => __('validation.array', ['attribute' => __('languages')])],
        ],
        'Languages is empty' => fn () => [
            'state' => ['languages' => []],
            'errors' => ['languages' => __('validation.required', ['attribute' => __('languages')])],
        ],
    ];
});
