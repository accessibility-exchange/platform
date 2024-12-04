<?php

dataset('storeEngagementLanguagesRequestValidationErrors', function () {
    return [
        'Languages is missing' => [
            'state' => ['languages' => null],
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
        'Language is invalid' => [
            'state' => ['languages' => ['xyz']],
            'errors' => fn () => ['languages.0' => __('validation.exists', ['attribute' => __('languages')])],
        ],
    ];
});
