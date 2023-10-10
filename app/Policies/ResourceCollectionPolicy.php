<?php

namespace App\Policies;

use App\Models\ResourceCollection;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ResourceCollectionPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->isAdministrator() ? true : null;
    }

    public function update(User $user, ResourceCollection $resourceCollection): Response
    {
        return Response::deny(__('You cannot edit this resource collection.'));
    }
}
