<?php

dataset('storeInvitationRequestValidationErrors', function () {
    return [
        'missing email' => [
            ['email' => null],
            fn () => ['email' => __('You must enter an email address.')],
        ],
        'invitation already sent' => [
            ['email' => 'invitation.sent.test@example.com'],
            fn () => ['email' => __('This member has already been invited.')],
        ],
        'user already a member' => [
            ['email' => 'invitation.existing.member.test@example.com'],
            fn () => ['email' => __('This member already belongs to this organization.')],
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
