<?php

namespace App\Policies;

use App\Models\Criterion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CriterionPolicy
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
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Criterion  $criterion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Criterion $criterion)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Criterion  $criterion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Criterion $criterion)
    {
        return $user->can('update', $criterion->matchingStrategy());
    }

    /**$strateg
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Criterion  $criterion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Criterion $criterion)
    {
        return $user->can('update', $criterion->matchingStrategy());
    }
}
