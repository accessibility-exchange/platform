<?php

namespace App\Policies;

use App\Models\Engagement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EngagementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Engagement  $engagement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Engagement $engagement)
    {
        return true;
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
}
