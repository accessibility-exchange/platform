<?php

namespace App\Observers;

use App\Enums\IndividualRole;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\IndividualContractorInvited;
use App\Notifications\ParticipantInvited;
use ParagonIE\CipherSweet\CipherSweet as CipherSweetEngine;
use ParagonIE\CipherSweet\EncryptedField;

class UserObserver
{
    public function created(User $user): void
    {
        $email = (new EncryptedField(
            app(CipherSweetEngine::class),
            'users',
            'email'
        ))->decryptValue($user->email);

        foreach (
            Invitation::where([
                ['email', $email],
                ['role', IndividualRole::ConsultationParticipant->value],
            ])->get() as $invitation
        ) {
            $user->notify(new ParticipantInvited($invitation, true));
        }

        foreach (
            Invitation::where([
                ['email', $email],
                ['role', IndividualRole::CommunityConnector->value],
            ])->get() as $invitation
        ) {
            $user->notify(new IndividualContractorInvited($invitation, true));
        }
    }
}
