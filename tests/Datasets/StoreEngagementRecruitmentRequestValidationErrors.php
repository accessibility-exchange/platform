<?php

dataset('storeEngagementRecruitmentRequestValidationErrors', function () {
    return array_map('array_values', [
        'Recruitment is missing' => [
            'state' => ['recruitment' => null],
            'errors' => fn () => ['recruitment' => __('validation.required', ['attribute' => __('recruitment method')])],
        ],
        'Recruitment is invalid' => [
            'state' => ['recruitment' => ['xyz']],
            'errors' => fn () => ['recruitment' => __('validation.exists', ['attribute' => __('recruitment method')])],
        ],
    ]);
});
