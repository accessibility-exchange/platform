<?php

dataset('unblockRequestValidationErrors', function () {
    return [
        'Blockable type is missing' => fn () => [
            'state' => ['blockable_type' => null],
            'errors' => ['blockable_type' => __('validation.required', ['attribute' => __('blockable type')])],
        ],
        'Blockable type is not a string' => fn () => [
            'state' => ['blockable_type' => false],
            'errors' => ['blockable_type' => __('validation.string', ['attribute' => __('blockable type')])],
        ],
        'Blockable type is invalid' => fn () => [
            'state' => ['blockable_type' => 'App\Models\User'],
            'errors' => ['blockable_type' => __('validation.exists', ['attribute' => __('blockable type')])],
        ],
        'Blockable id is missing' => fn () => [
            'state' => ['blockable_id' => null],
            'errors' => ['blockable_id' => __('validation.required', ['attribute' => __('blockable id')])],
        ],
        'Blockable id is not an integer' => fn () => [
            'state' => ['blockable_id' => 'not an integer'],
            'errors' => ['blockable_id' => __('validation.integer', ['attribute' => __('blockable id')])],
        ],
        'Blockable id invalid' => fn () => [
            'state' => ['blockable_id' => 1000],
            'errors' => ['blockable_id' => __('validation.exists', ['attribute' => __('blockable id')])],
        ],
    ];
});
