<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can select a role.
     *
     * @param User $user
     * @return Response
     */
    public function selectRole(User $user): Response
    {
        return ! is_null($user->communityMember)
            ? Response::allow()
            : Response::deny(__('You cannot select a role.'));
    }
}
