<?php

dataset('inviteParticipantValidationErrors', function () {
    return [
        'Email is missing' => [
            'state' => ['email' => null],
            'errors' => fn () => ['email' => __('You must enter an email address.')],
        ],
        'User already invited' => [
            'state' => ['email' => 'invited@example.com'],
            'errors' => fn () => ['email' => __('This individual has already been invited to your engagement.')],
        ],
        'User already added to engagement' => [
            'state' => ['email' => 'existing@example.com'],
            'errors' => fn () => ['email' => __('The individual with the email address you provided is already participating in this engagement.')],
        ],
        'User not a consultation participant' => [
            'state' => ['email' => 'not-participant@example.com'],
            'errors' => fn () => ['email' => __('The person with the email address you provided is not a consultation participant.')],
        ],
        'Email address not for an individual user' => [
            'state' => ['email' => 'not-individual@example.com'],
            'errors' => fn () => ['email' => __('The person with the email address you provided is not a consultation participant.')],
        ],
    ];
});
