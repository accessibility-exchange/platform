<?php

dataset('storeEngagementRequestValidationErrors', function () {
    return [
        'Project id is missing' => [
            'state' => ['project_id' => null],
            'errors' => fn () => ['project_id' => __('validation.required', ['attribute' => __('project id')])],
        ],
        'Project id is invalid' => [
            'state' => ['project_id' => 100000],
            'errors' => fn () => ['project_id' => __('validation.exists', ['attribute' => __('project id')])],
        ],
        'Name is not a string' => [
            'state' => ['name' => ['en' => 123]],
            'errors' => fn () => ['name.en' => __('validation.string', ['attribute' => __('engagement name (English)')])],
        ],
        'Name is missing required translation' => [
            'state' => ['name' => ['es' => 'Nombre del compromiso']],
            'errors' => fn () => [
                'name.en' => __('An engagement name must be provided in at least one language.'),
                'name.fr' => __('An engagement name must be provided in at least one language.'),
            ],
        ],
        'Who is missing' => [
            'state' => ['who' => null],
            'errors' => fn () => ['who' => __('You must indicate who you want to engage.')],
        ],
        'Who is invalid' => [
            'state' => ['who' => 'other'],
            'errors' => fn () => ['who' => __('validation.exists', ['attribute' => __('who')])],
        ],
    ];
});
