<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\IndividualContractorInvited;
use App\Notifications\ParticipantInvited;

class UserObserver
{
    public function created(User $user): void
    {
        foreach ($user->participantInvitations() as $invitation) {
            $user->notify(new ParticipantInvited($invitation, true));
        }

        foreach ($user->contractorInvitations() as $invitation) {
            $user->notify(new IndividualContractorInvited($invitation, true));
        }
    }
}
