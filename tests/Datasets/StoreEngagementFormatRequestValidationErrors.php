<?php

dataset('storeEngagementFormatRequestValidationErrors', function () {
    return [
        'Format is missing' => fn () => [
            'state' => ['format' => null],
            'errors' => ['format' => __('validation.required', ['attribute' => __('engagement format')])],
        ],
        'Format is invalid' => fn () => [
            'state' => ['format' => ['xyz']],
            'errors' => ['format' => __('validation.exists', ['attribute' => __('engagement format')])],
        ],
    ];
});
