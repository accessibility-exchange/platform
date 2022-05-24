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

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param User $model
     * @return Response
     */
    public function update(User $user, User $model): Response
    {
        return $user->id === $model->id
            ? Response::allow()
            : Response::deny(__('You cannot edit this account.'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param User $model
     * @return Response
     */
    public function delete(User $user, User $model): Response
    {
        return $user->id === $model->id
            ? Response::allow()
            : Response::deny(__('You cannot delete this account.'));
    }
}
