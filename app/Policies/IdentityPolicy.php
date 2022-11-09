<?php

namespace App\Policies;

use App\Models\Identity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IdentityPolicy
{
    use HandlesAuthorization;

    public function before(User $user): null|bool
    {
        return $user->isAdministrator() ? true : null;
    }

    public function viewAny(User $user)
    {
        return false;
    }

    public function view(User $user, Identity $Identity)
    {
        return false;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Identity $Identity)
    {
        return false;
    }

    public function delete(User $user, Identity $Identity)
    {
        return false;
    }
}
