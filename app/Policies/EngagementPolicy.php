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
        //
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
        //
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
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Engagement  $engagement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Engagement $engagement)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Engagement  $engagement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Engagement $engagement)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Engagement  $engagement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Engagement $engagement)
    {
        //
    }
}
