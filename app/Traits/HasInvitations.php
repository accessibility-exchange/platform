<?php

namespace App\Traits;

use App\Models\Invitation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasInvitations
{
    /**
     * Get the invitations associated with this organization.
     */
    public function invitations(): MorphMany
    {
        return $this->morphMany(Invitation::class, 'invitationable');
    }
}
