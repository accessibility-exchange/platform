<?php

namespace App\Policies;

use App\Models\Library;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LibraryPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->isAdministrator() ? true : null;
    }

    public function update(User $user, Library $library): Response
    {
        return Response::deny(__('You cannot edit this library.'));
    }
}
