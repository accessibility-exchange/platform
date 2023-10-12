<?php

namespace App\Policies;

use App\Models\MatchingStrategy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MatchingStrategyPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->isAdministrator() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, MatchingStrategy $matchingStrategy): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, MatchingStrategy $matchingStrategy): bool
    {
        return $user->can('update', $matchingStrategy->matchable());
    }

    public function delete(User $user, MatchingStrategy $matchingStrategy): bool
    {
        return $user->can('update', $matchingStrategy->matchable());
    }
}
