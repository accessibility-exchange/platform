<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AreaTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, AgeBracket $ageBracket): bool
    {
        return false;
    }

    public function delete(User $user, AgeBracket $ageBracket): bool
    {
        return false;
    }
}
