<?php

namespace App\Policies;

use App\Models\MatchingStrategy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MatchingStrategyPolicy
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
     * @param  \App\Models\MatchingStrategy  $matchingStrategy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MatchingStrategy $matchingStrategy)
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
     * @param  \App\Models\MatchingStrategy  $matchingStrategy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MatchingStrategy $matchingStrategy)
    {
        return $user->can('update', $matchingStrategy->matchable);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MatchingStrategy  $matchingStrategy
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MatchingStrategy $matchingStrategy)
    {
        return $user->can('update', $matchingStrategy->matchable);
    }
}
