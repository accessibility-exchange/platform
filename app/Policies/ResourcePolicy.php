<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResourcePolicy
{
    use HandlesAuthorization;

    public function before(User $user): null|bool
    {
        return $user->isAdministrator() ? true : null;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Resource $resource): bool
    {
        return false;
    }

    public function delete(User $user, Resource $resource): bool
    {
        return false;
    }
}
