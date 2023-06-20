<?php

use App\Enums\TeamRole;

dataset('storeInvitationRequestValidationErrors', function () {
    $baseData = [
        'email' => 'invitation.user.test@example.com',
        'role' => TeamRole::Member->value,
    ];

    return [
        'missing email' => [
            array_merge($baseData, ['email' => null]),
            fn () => ['email' => __('The user’s email address is missing.')],
        ],
        'invitation already sent' => [
            array_merge($baseData, ['email' => 'invitation.sent.test@example.com']),
            fn () => ['email' => __('validation.unique', ['attribute' => __('email address')])],
        ],
        'user already a member' => [
            array_merge($baseData, ['email' => 'invitation.existing.member.test@example.com']),
            fn () => ['email' => __('This user already belongs to this team.')],
        ],
        'role missing' => [
            array_merge($baseData, ['role' => null]),
            fn () => ['role' => __('The user’s role is missing.')],
        ],
        'invalid role' => [
            array_merge($baseData, ['role' => 'fake']),
            fn () => ['role' => __('validation.in', ['attribute' => 'role'])],
        ],
    ];
});
