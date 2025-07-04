<?php

namespace App\Policies;

use App\Models\Tool;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ToolPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->isAdministrator() ? true : null;
    }

    public function update(User $user, Tool $library): Response
    {
        return Response::deny(__('You cannot edit this tool.'));
    }
}
