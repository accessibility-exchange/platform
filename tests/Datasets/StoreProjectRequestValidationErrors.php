<?php

dataset('storeProjectRequestValidationErrors', function () {
    return [
        'Projectable type is missing' => fn () => [
            'state' => ['projectable_type' => null],
            'errors' => ['projectable_type' => __('validation.required', ['attribute' => __('projectable type')])],
        ],
        'Projectable type is not a string' => fn () => [
            'state' => ['projectable_type' => false],
            'errors' => ['projectable_type' => __('validation.string', ['attribute' => __('projectable type')])],
        ],
        'Projectable type is not valid' => fn () => [
            'state' => ['projectable_type' => 'projectable'],
            'errors' => ['projectable_type' => __('validation.exists', ['attribute' => __('projectable type')])],
        ],
        'Projectable id is missing' => fn () => [
            'state' => ['projectable_id' => null],
            'errors' => ['projectable_id' => __('validation.required', ['attribute' => __('projectable id')])],
        ],
        'Projectable id is not an integer' => fn () => [
            'state' => ['projectable_id' => false],
            'errors' => ['projectable_id' => __('validation.integer', ['attribute' => __('projectable id')])],
        ],
        'Projectable id is not valid' => fn () => [
            'state' => ['projectable_id' => 1000000],
            'errors' => ['projectable_id' => __('validation.exists', ['attribute' => __('projectable id')])],
        ],
        'Ancestor id is not an integer' => fn () => [
            'state' => ['ancestor_id' => false],
            'errors' => ['ancestor_id' => __('validation.integer', ['attribute' => __('previous project id')])],
        ],
        'Ancestor id is not valid' => fn () => [
            'state' => ['ancestor_id' => 1000000],
            'errors' => ['ancestor_id' => __('validation.exists', ['attribute' => __('previous project id')])],
        ],
        'Name is missing' => fn () => [
            'state' => ['name' => null],
            'errors' => [
                'name.en' => __('A project name must be provided in at least one language.'),
                'name.fr' => __('A project name must be provided in at least one language.'),
            ],
        ],
        'Name is missing required translation' => fn () => [
            'state' => ['name' => ['es' => 'Nombre del proyecto']],
            'errors' => [
                'name.en' => __('A project name must be provided in at least one language.'),
                'name.fr' => __('A project name must be provided in at least one language.'),
            ],
            'without' => ['name'],
        ],
        'Name translation is not a string' => fn () => [
            'state' => ['name.en' => false],
            'errors' => ['name.en' => __('validation.string', ['attribute' => __('project name (English)')])],
        ],
    ];
});
