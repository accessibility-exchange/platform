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

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ResourceCollection $resourceCollection): Response
    {
        return $user->id === $resourceCollection->user_id
            ? Response::allow()
            : Response::deny(__('You cannot edit this resource collection.'));
    }

    public function delete(User $user, ResourceCollection $resourceCollection): Response
    {
        return $user->id === $resourceCollection->user_id
            ? Response::allow()
            : Response::deny(__('You cannot delete this resource collection.'));
    }
}
