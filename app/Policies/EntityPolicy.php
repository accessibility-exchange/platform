<?php

namespace App\Policies;

use App\Models\Entity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EntityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Entity  $entity
     * @return mixed
     */
    public function update(User $user, Entity $entity)
    {
        return $user->isAdministratorOf($entity)
            ? Response::allow()
            : Response::deny('You cannot edit this entity.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Entity  $entity
     * @return mixed
     */
    public function delete(User $user, Entity $entity)
    {
        return $user->isAdministratorOf($entity)
            ? Response::allow()
            : Response::deny('You cannot delete this entity.');
    }
}
