<?php

dataset('storeProjectLanguagesRequestValidationErrors', function () {
    return [
        'Languages type is missing' => fn () => [
            'state' => ['languages' => null],
            'errors' => ['languages' => __('validation.required', ['attribute' => __('project languages')])],
        ],
        'Languages is not an array' => fn () => [
            'state' => ['languages' => false],
            'errors' => ['languages' => __('validation.array', ['attribute' => __('project languages')])],
        ],
        'Languages array is empty' => fn () => [
            'state' => ['languages' => []],
            'errors' => ['languages' => __('validation.required', ['attribute' => __('project languages')])],
        ],
    ];
});
