<?php

dataset('storeAccessNeedsPermissionsValidationErrors', function () {
    return [
        'Share access needs is missing' => fn () => [
            'state' => [],
            'errors' => ['share_access_needs' => __('validation.required', ['attribute' => __('share access needs')])],
        ],
        'Share access needs is not a boolean' => fn () => [
            'state' => ['share_access_needs' => 123],
            'errors' => ['share_access_needs' => __('validation.boolean', ['attribute' => __('share access needs')])],
        ],
    ];
});
