<?php

namespace App\Policies;

use App\Models\DisabilityType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DisabilityTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): Response
    {
        return $user->context === 'administrator'
            ? Response::allow()
            : Response::deny();
    }

//    public function create(User $user): bool
//    {
//        return false;
//    }
//
//    public function update(User $user, DisabilityType $disabilityType): bool
//    {
//        return false;
//    }
//
//    public function delete(User $user, DisabilityType $disabilityType): bool
//    {
//        return false;
//    }
}
