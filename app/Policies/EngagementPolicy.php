<?php

namespace App\Policies;

use App\Models\Engagement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EngagementPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Engagement $engagement): Response
    {
        return
            $user->individual || $user->organization || $user->regulated_organization
            && $engagement->checkStatus('published') || $user->can('update', $engagement)
                ? Response::allow()
                : Response::denyAsNotFound();
    }

    public function update(User $user, Engagement $engagement): Response
    {
        return $user->can('update', $engagement->project)
            ? Response::allow()
            : Response::deny();
    }

    public function addConnector(User $user, Engagement $engagement): Response
    {
        return
            $user->can('update', $engagement)
            && ! $engagement->connector
            && ! $engagement->organizationalConnector
            && ! $engagement->invitations->where('role', 'connector')->count()
                ? Response::allow()
                : Response::deny();
    }

    public function participate(User $user, Engagement $engagement): Response
    {
        return $engagement->confirmedParticipants->contains($user->individual)
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
