<?php

namespace App\Policies;

use App\Models\EthnoracialIdentity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EthnoracialIdentityPolicy
{
    use HandlesAuthorization;

    public function before(User $user): null|bool
    {
        return $user->isAdministrator() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

//    public function create(User $user): bool
//    {
//        return false;
//    }
//
//    public function update(User $user, EthnoracialIdentity $ethnoracialIdentity): bool
//    {
//        return false;
//    }
//
//    public function delete(User $user, EthnoracialIdentity $ethnoracialIdentity): bool
//    {
//        return false;
//    }
}
