<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ResourcePolicy
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
     * @param  \App\Models\Resource  $resource
     * @return mixed
     */
    public function update(User $user, Resource $resource)
    {
        return $user->id === $resource->user_id
            ? Response::allow()
            : Response::deny('You cannot edit this resource.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Resource  $resource
     * @return mixed
     */
    public function delete(User $user, Resource $resource)
    {
        return $user->id === $resource->user_id
            ? Response::allow()
            : Response::deny('You cannot delete this resource.');
    }
}
