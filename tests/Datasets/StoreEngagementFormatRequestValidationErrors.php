<?php

dataset('storeEngagementFormatRequestValidationErrors', function () {
    return array_map('array_values', [
        'Format is missing' => [
            'state' => ['format' => null],
            'errors' => fn () => ['format' => __('validation.required', ['attribute' => __('engagement format')])],
        ],
        'Format is invalid' => [
            'state' => ['format' => ['xyz']],
            'errors' => fn () => ['format' => __('validation.exists', ['attribute' => __('engagement format')])],
        ],
    ]);
});
