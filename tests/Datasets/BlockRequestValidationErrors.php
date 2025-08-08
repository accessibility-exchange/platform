<?php

use App\Models\User;

dataset('blockRequestValidationErrors', function () {
    return [
        'Blockable type is missing' => fn () => [
            'state' => ['blockable_type' => null],
        ],
        'Blockable type is not a string' => fn () => [
            'state' => ['blockable_type' => false],
        ],
        'Blockable type is invalid' => fn () => [
            'state' => [
                'blockable_type' => 'App\Models\User',
                'blockable_id' => User::firstOr(function () {
                    return User::factory()->create();
                })->id,
            ],
            'errors' => ['blockable_type' => __('validation.exists', ['attribute' => __('blockable type')])],
        ],
        'Blockable id is missing' => fn () => [
            'state' => ['blockable_id' => null],
        ],
        'Blockable id is not an integer' => fn () => [
            'state' => ['blockable_id' => 'not an integer'],
        ],
        'Blockable id invalid' => fn () => [
            'state' => ['blockable_id' => 1000],
        ],
    ];
});
