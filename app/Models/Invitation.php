<?php

namespace App\Models;

use Hearth\Models\Invitation as HearthInvitation;

/**
 * @property ?string $type
 */
class Invitation extends HearthInvitation
{
    protected $table = 'invitations';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->mergeFillable(['type']);
    }

    public function accept(string $type = 'individual'): void
    {
        if ($type === 'individual') {
            $invitee = User::whereBlind('email', 'email_index', $this->email)->first();
            if ($this->role === 'connector') {
                $this->invitationable->connector()->associate($invitee->individual);
                $this->invitationable->save();
            }
        } else {
            $invitee = Organization::where('contact_person_email', $this->email)->first();
            if ($this->role === 'connector') {
                $this->invitationable->organizationalConnector()->associate($invitee);
                $this->invitationable->save();
            }
        }

        $this->delete();
    }
}
