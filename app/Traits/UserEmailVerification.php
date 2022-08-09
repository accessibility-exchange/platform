<?php

namespace App\Traits;

trait UserEmailVerification
{
    protected function updateVerifiedUser(mixed $user, $email): void
    {
        $user->forceFill([
            'email' => $email,
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
