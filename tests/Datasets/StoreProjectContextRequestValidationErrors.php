<?php

use App\Enums\ProjectContext;

dataset('storeProjectContextRequestValidationErrors', function () {
    return [
        'Context type is missing' => fn () => [
            'state' => ['context' => null],
            'errors' => ['context' => __('validation.required', ['attribute' => __('project context')])],
        ],
        'Context is not a string' => fn () => [
            'state' => ['context' => false],
            'errors' => ['context' => __('validation.string', ['attribute' => __('project context')])],
        ],
        'Context is not valid' => fn () => [
            'state' => ['context' => 'old'],
            'errors' => ['context' => __('validation.exists', ['attribute' => __('project context')])],
        ],
        'Ancestor is not an integer' => fn () => [
            'state' => ['ancestor' => false, 'context' => ProjectContext::New->value],
            'errors' => ['ancestor' => __('validation.integer', ['attribute' => __('previous project')])],
        ],
        'Ancestor is missing' => fn () => [
            'state' => ['ancestor' => null, 'context' => ProjectContext::FollowUp->value],
            'errors' => ['ancestor' => __('Since this is a follow-up to a previous project, you must specify the previous project.')],
        ],
        'Ancestor is invalid' => fn () => [
            'state' => ['ancestor' => 1000000, 'context' => ProjectContext::New->value],
            'errors' => ['ancestor' => __('validation.exists', ['attribute' => __('previous project')])],
        ],
    ];
});
