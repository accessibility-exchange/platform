<?php

namespace App\Models;

use Hearth\Models\Invitation as HearthInvitation;

class Invitation extends HearthInvitation
{
    protected $table = 'invitations';

    public function accept(): void
    {
        $invitee = User::whereBlind('email', 'email_index', $this->email)->first();

        $this->invitationable->users()->attach(
            $invitee,
            ['role' => $this->role]
        );

        $this->delete();
    }
}
