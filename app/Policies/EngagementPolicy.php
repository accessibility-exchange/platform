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

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Engagement  $engagement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Engagement $engagement)
    {
        return $user->can('update', $engagement->project);
    }

    /**
     * Determine whether the user can manage the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Engagement  $engagement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function participate(User $user, Engagement $engagement)
    {
        return $engagement->confirmedParticipants->contains($user->individual);
    }
}
