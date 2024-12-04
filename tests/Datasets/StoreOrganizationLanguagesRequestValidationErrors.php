<?php

dataset('storeOrganizationLanguagesRequestValidationErrors', function () {
    return [
        'Languages is missing' => [
            'state' => ['roles' => null],
            'errors' => fn () => ['languages' => __('validation.required', ['attribute' => __('languages')])],
        ],
        'Languages is not an array' => [
            'state' => ['languages' => false],
            'errors' => fn () => ['languages' => __('validation.array', ['attribute' => __('languages')])],
        ],
        'Languages is empty' => [
            'state' => ['languages' => []],
            'errors' => fn () => ['languages' => __('validation.required', ['attribute' => __('languages')])],
        ],
    ];
});
