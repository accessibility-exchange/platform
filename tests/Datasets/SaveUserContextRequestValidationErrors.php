<?php

use App\Enums\UserContext;

dataset('saveUserContextRequestValidationErrors', function () {
    return [
        'Context is missing' => fn () => [
            'state' => ['context' => null],
            'errors' => ['context' => __('You must tell us who you’re joining as.')],
        ],
        'Context is not a string' => fn () => [
            'state' => ['context' => false],
            'errors' => ['context' => __('validation.string', ['attribute' => __('context')])],
        ],
        'Context is invalid' => fn () => [
            'state' => ['context' => 'invalid'],
            'errors' => ['context' => __('validation.exists', ['attribute' => __('context')])],
        ],
        'Context is an administrator' => fn () => [
            'state' => ['context' => UserContext::Administrator->value],
            'errors' => ['context' => __('validation.exists', ['attribute' => __('context')])],
        ],
    ];
});
