<?php

namespace App\Policies;

use App\Models\Criterion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CriterionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return false;
    }

    public function view(User $user, Criterion $criterion)
    {
        return false;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Criterion $criterion)
    {
        return $user->can('update', $criterion->matchingStrategy());
    }

    public function delete(User $user, Criterion $criterion)
    {
        return $user->can('update', $criterion->matchingStrategy());
    }
}
