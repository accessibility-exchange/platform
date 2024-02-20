<?php

dataset('storeAccessNeedsPermissionsValidationErrors', function () {
    return [
        'Share access needs is missing' => [
            'state' => [],
            'errors' => fn () => ['share_access_needs' => __('validation.required', ['attribute' => __('share access needs')])],
        ],
        'Share access needs is not a boolean' => [
            'state' => ['share_access_needs' => 123],
            'errors' => fn () => ['share_access_needs' => __('validation.boolean', ['attribute' => __('share access needs')])],
        ],
    ];
});
