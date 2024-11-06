<?php

dataset('storeProjectLanguagesRequestValidationErrors', function () {
    return array_map('array_values', [
        'Languages type is missing' => [
            'state' => ['languages' => null],
            'errors' => fn () => ['languages' => __('validation.required', ['attribute' => __('project languages')])],
        ],
        'Languages is not an array' => [
            'state' => ['languages' => false],
            'errors' => fn () => ['languages' => __('validation.array', ['attribute' => __('project languages')])],
        ],
        'Languages array is empty' => [
            'state' => ['languages' => []],
            'errors' => fn () => ['languages' => __('validation.required', ['attribute' => __('project languages')])],
        ],
    ]);
});
