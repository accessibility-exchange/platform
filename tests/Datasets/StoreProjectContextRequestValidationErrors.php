<?php

dataset('storeProjectContextRequestValidationErrors', function () {
    return [
        'Context type is missing' => [
            'state' => ['context' => null],
            'errors' => fn () => ['context' => __('validation.required', ['attribute' => __('project context')])],
        ],
        'Context is not a string' => [
            'state' => ['context' => false],
            'errors' => fn () => ['context' => __('validation.string', ['attribute' => __('project context')])],
        ],
        'Context is not valid' => [
            'state' => ['context' => 'old'],
            'errors' => fn () => ['context' => __('validation.exists', ['attribute' => __('project context')])],
        ],
        'Ancestor is not an integer' => [
            'state' => ['ancestor' => false, 'context' => 'new'],
            'errors' => fn () => ['ancestor' => __('validation.integer', ['attribute' => __('previous project')])],
        ],
        'Ancestor is missing' => [
            'state' => ['ancestor' => null, 'context' => 'follow-up'],
            'errors' => fn () => ['ancestor' => __('Since this is a follow-up to a previous project, you must specify the previous project.')],
        ],
        'Ancestor is invalid' => [
            'state' => ['ancestor' => 1000000, 'context' => 'new'],
            'errors' => fn () => ['ancestor' => __('validation.exists', ['attribute' => __('previous project')])],
        ],
    ];
});
