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

    public function accept(?string $type = null): void
    {
        if ($type) {
            if ($type === 'individual') {
                $user = User::whereBlind('email', 'email_index', $this->email)->first();
                $invitee = $user->individual;
                if ($this->role === 'connector') {
                    $this->invitationable->connector()->associate($invitee);
                    $this->invitationable->save();
                }
                if ($this->role === 'participant') {
                    $this->invitationable->participants()->save($invitee);
                }
            }
            if ($type === 'organization') {
                $invitee = Organization::where('contact_person_email', $this->email)->first();
                if ($this->role === 'connector') {
                    $this->invitationable->organizationalConnector()->associate($invitee);
                    $this->invitationable->save();
                }
            }
        } else {
            $invitee = User::whereBlind('email', 'email_index', $this->email)->first();

            $this->invitationable->users()->attach(
                $invitee,
                ['role' => $this->role]
            );
        }

        $this->delete();
    }
}
