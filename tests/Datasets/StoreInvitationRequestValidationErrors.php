<?php

dataset('storeInvitationRequestValidationErrors', function () {
    return [
        'missing email' => [
            ['email' => null],
            fn () => ['email' => __('The user’s email address is missing.')],
        ],
        'invitation already sent' => [
            ['email' => 'invitation.sent.test@example.com'],
            fn () => ['email' => __('validation.unique', ['attribute' => __('email address')])],
        ],
        'user already a member' => [
            ['email' => 'invitation.existing.member.test@example.com'],
            fn () => ['email' => __('This user already belongs to this team.')],
        ],
        'role missing' => [
            ['role' => null],
            fn () => ['role' => __('The user’s role is missing.')],
        ],
        'invalid role' => [
            ['role' => 'fake'],
            fn () => ['role' => __('validation.in', ['attribute' => 'role'])],
        ],
    ];
});
