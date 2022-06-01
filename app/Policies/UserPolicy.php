<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public function selectRole(User $user): Response
    {
        return ! is_null($user->individual)
            ? Response::allow()
            : Response::deny(__('You cannot select a role.'));
    }
}
