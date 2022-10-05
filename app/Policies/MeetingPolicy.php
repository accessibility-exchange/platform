<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MeetingPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Meeting $meeting): Response
    {
        return $user->can('update', $meeting->engagement)
            ? Response::allow()
            : Response::deny();
    }

    public function delete(User $user, Meeting $meeting): Response
    {
        return $user->can('update', $meeting->engagement)
            ? Response::allow()
            : Response::deny();
    }
}
