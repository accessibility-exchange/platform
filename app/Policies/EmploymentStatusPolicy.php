<?php

namespace App\Policies;

use App\Models\EmploymentStatus;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EmploymentStatusPolicy
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
//    public function update(User $user, EmploymentStatus $employmentStatus): bool
//    {
//        return false;
//    }
//
//    public function delete(User $user, EmploymentStatus $employmentStatus): bool
//    {
//        return false;
//    }
}
