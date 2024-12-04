<?php

dataset('storeEngagementRequestValidationErrors', function () {
    return [
        'Project id is missing' => fn () => [
            'state' => ['project_id' => null],
            'errors' => ['project_id' => __('validation.required', ['attribute' => __('project id')])],
        ],
        'Project id is invalid' => fn () => [
            'state' => ['project_id' => 100000],
            'errors' => ['project_id' => __('validation.exists', ['attribute' => __('project id')])],
        ],
        'Name is not a string' => fn () => [
            'state' => ['name' => ['en' => 123]],
            'errors' => ['name.en' => __('validation.string', ['attribute' => __('engagement name (English)')])],
        ],
        'Name is missing required translation' => fn () => [
            'state' => ['name' => ['es' => 'Nombre del compromiso']],
            'errors' => [
                'name.en' => __('An engagement name must be provided in at least one language.'),
                'name.fr' => __('An engagement name must be provided in at least one language.'),
            ],
        ],
        'Who is missing' => fn () => [
            'state' => ['who' => null],
            'errors' => ['who' => __('You must indicate who you want to engage.')],
        ],
        'Who is invalid' => fn () => [
            'state' => ['who' => 'other'],
            'errors' => ['who' => __('validation.exists', ['attribute' => __('who')])],
        ],
    ];
});
