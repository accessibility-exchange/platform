<?php

dataset('storeProjectRequestValidationErrors', function () {
    return [
        'Projectable type is missing' => [
            'state' => ['projectable_type' => null],
            'errors' => fn () => ['projectable_type' => __('validation.required', ['attribute' => __('projectable type')])],
        ],
        'Projectable type is not a string' => [
            'state' => ['projectable_type' => false],
            'errors' => fn () => ['projectable_type' => __('validation.string', ['attribute' => __('projectable type')])],
        ],
        'Projectable type is not valid' => [
            'state' => ['projectable_type' => 'projectable'],
            'errors' => fn () => ['projectable_type' => __('validation.exists', ['attribute' => __('projectable type')])],
        ],
        'Projectable id is missing' => [
            'state' => ['projectable_id' => null],
            'errors' => fn () => ['projectable_id' => __('validation.required', ['attribute' => __('projectable id')])],
        ],
        'Projectable id is not an integer' => [
            'state' => ['projectable_id' => false],
            'errors' => fn () => ['projectable_id' => __('validation.integer', ['attribute' => __('projectable id')])],
        ],
        'Projectable id is not valid' => [
            'state' => ['projectable_id' => 1000000],
            'errors' => fn () => ['projectable_id' => __('validation.exists', ['attribute' => __('projectable id')])],
        ],
        'Ancestor id is not an integer' => [
            'state' => ['ancestor_id' => false],
            'errors' => fn () => ['ancestor_id' => __('validation.integer', ['attribute' => __('previous project id')])],
        ],
        'Ancestor id is not valid' => [
            'state' => ['ancestor_id' => 1000000],
            'errors' => fn () => ['ancestor_id' => __('validation.exists', ['attribute' => __('previous project id')])],
        ],
        'Name is missing' => [
            'state' => ['name' => null],
            'errors' => fn () => [
                'name.en' => __('A project name must be provided in at least one language.'),
                'name.fr' => __('A project name must be provided in at least one language.'),
            ],
        ],
        'Name is missing required translation' => [
            'state' => ['name' => ['es' => 'Nombre del proyecto']],
            'errors' => fn () => [
                'name.en' => __('A project name must be provided in at least one language.'),
                'name.fr' => __('A project name must be provided in at least one language.'),
            ],
            'without' => ['name'],
        ],
        'Name translation is not a string' => [
            'state' => ['name.en' => false],
            'errors' => fn () => ['name.en' => __('validation.string', ['attribute' => __('project name (English)')])],
        ],
    ];
});
