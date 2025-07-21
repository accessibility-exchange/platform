<?php

dataset('updateNotificationPreferencesRequestValidationErrors', function () {
    return [
        'Notification settings: engagements is missing' => fn () => [
            'state' => ['notification_settings' => ['engagements' => null]],
            'errors' => ['notification_settings.engagements' => __('validation.required', ['attribute' => __('engagements notification setting')])],
        ],
        'Notification settings: engagements is not boolean' => fn () => [
            'state' => ['notification_settings' => ['engagements' => 'invalid']],
            'errors' => ['notification_settings.engagements' => __('validation.boolean', ['attribute' => __('engagements notification setting')])],
        ],
    ];
});
