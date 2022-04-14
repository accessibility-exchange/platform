<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can select a role.
     *
     * @param User $user
     * @return bool
     */
    public function selectRole(User $user): bool
    {
        return $user->context === 'community-member';
    }
}
