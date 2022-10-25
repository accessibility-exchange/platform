<?php

namespace App\Policies;

use App\Models\DefinedTerm;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefinedTermPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

//    public function view(User $user, DefinedTerm $definedTerm): bool
//    {
//        return true;
//    }
//
//    public function create(User $user): bool
//    {
//        return false;
//    }
//
//    public function update(User $user, DefinedTerm $definedTerm): bool
//    {
//        return false;
//    }
//
//    public function delete(User $user, DefinedTerm $definedTerm): bool
//    {
//        return false;
//    }
}
