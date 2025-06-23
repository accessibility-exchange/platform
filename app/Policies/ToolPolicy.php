<?php

namespace App\Policies;

use App\Models\Tool;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ToolPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdministrator() ? true : null;
    }

    public function update(User $user, Tool $tool): Response
    {
        return Response::deny(__('You cannot edit this tool.'));
    }
}
