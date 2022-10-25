<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ResourcePolicy
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

    public function update(User $user, Resource $resource): Response
    {
        return $user->id === $resource->user_id
            ? Response::allow()
            : Response::deny(__('You cannot edit this resource.'));
    }

    public function delete(User $user, Resource $resource): Response
    {
        return $user->id === $resource->user_id
            ? Response::allow()
            : Response::deny(__('You cannot delete this resource.'));
    }
}
