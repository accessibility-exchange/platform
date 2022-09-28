<?php

namespace App\Policies;

use App\Models\ResourceCollection;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ResourceCollectionPolicy
{
    use HandlesAuthorization;

    public function before(User $user): null|bool
    {
        return $user->isAdministrator() ? true : null;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  ResourceCollection  $resourceCollection
     * @return Response
     */
    public function update(User $user, ResourceCollection $resourceCollection): Response
    {
        return $user->id === $resourceCollection->user_id
            ? Response::allow()
            : Response::deny('You cannot edit this resource collection.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  ResourceCollection  $resourceCollection
     * @return Response
     */
    public function delete(User $user, ResourceCollection $resourceCollection): Response
    {
        return $user->id === $resourceCollection->user_id
            ? Response::allow()
            : Response::deny('You cannot delete this resource collection.');
    }
}
