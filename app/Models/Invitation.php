<?php

namespace App\Models;

use App\Traits\RetrievesUserByNormalizedEmail;
use Hearth\Models\Invitation as HearthInvitation;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property ?string $type
 */
class Invitation extends HearthInvitation
{
    use RetrievesUserByNormalizedEmail;

    protected $table = 'invitations';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->mergeFillable(['type']);
    }

    public function email(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value),
        );
    }

    public function accept(?string $type = null): void
    {
        if ($type) {
            if ($type === 'individual') {
                $user = $this->retrieveUserByEmail($this->email);
                $invitee = $user->individual;
                if ($this->role === 'connector') {
                    $this->invitationable->connector()->associate($invitee);
                    $this->invitationable->save();
                }
                if ($this->role === 'participant') {
                    $this->invitationable->participants()->save($invitee, ['status' => 'confirmed']);
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
            $invitee = $this->retrieveUserByEmail($this->email);

            $this->invitationable->users()->attach(
                $invitee,
                ['role' => $this->role]
            );
        }

        $this->delete();
    }
}
