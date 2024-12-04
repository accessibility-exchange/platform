<?php

dataset('storeEngagementLanguagesRequestValidationErrors', function () {
    return [
        'Languages is missing' => fn () => [
            'state' => ['languages' => null],
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
        'Language is invalid' => fn () => [
            'state' => ['languages' => ['xyz']],
            'errors' => ['languages.0' => __('validation.exists', ['attribute' => __('languages')])],
        ],
    ];
});
