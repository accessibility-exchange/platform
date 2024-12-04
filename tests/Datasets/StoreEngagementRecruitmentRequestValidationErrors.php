<?php

dataset('storeEngagementRecruitmentRequestValidationErrors', function () {
    return [
        'Recruitment is missing' => fn () => [
            'state' => ['recruitment' => null],
            'errors' => ['recruitment' => __('validation.required', ['attribute' => __('recruitment method')])],
        ],
        'Recruitment is invalid' => fn () => [
            'state' => ['recruitment' => ['xyz']],
            'errors' => ['recruitment' => __('validation.exists', ['attribute' => __('recruitment method')])],
        ],
    ];
});
