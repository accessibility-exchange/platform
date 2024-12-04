<?php

dataset('inviteParticipantValidationErrors', function () {
    return [
        'Email is missing' => fn () => [
            'state' => ['email' => null],
            'errors' => ['email' => __('You must enter an email address.')],
        ],
        'User already invited' => fn () => [
            'state' => ['email' => 'invited@example.com'],
            'errors' => ['email' => __('This individual has already been invited to your engagement.')],
        ],
        'User already added to engagement' => fn () => [
            'state' => ['email' => 'existing@example.com'],
            'errors' => ['email' => __('The individual with the email address you provided is already participating in this engagement.')],
        ],
        'User not a consultation participant' => fn () => [
            'state' => ['email' => 'not-participant@example.com'],
            'errors' => ['email' => __('The person with the email address you provided is not a consultation participant.')],
        ],
        'Email address not for an individual user' => fn () => [
            'state' => ['email' => 'not-individual@example.com'],
            'errors' => ['email' => __('The person with the email address you provided is not a consultation participant.')],
        ],
    ];
});
