<?php

namespace App\Traits;

use App\Models\Invitation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasInvitations
{
    /** @return MorphMany<Invitation, $this> */
    public function invitations(): MorphMany
    {
        return $this->morphMany(Invitation::class, 'invitationable');
    }
}
