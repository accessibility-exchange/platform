<?php

namespace App\Policies;

use App\Models\EmploymentStatus;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmploymentStatusPolicy
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

    public function update(User $user, EmploymentStatus $employmentStatus): bool
    {
        return false;
    }

    public function delete(User $user, EmploymentStatus $employmentStatus): bool
    {
        return false;
    }
}
