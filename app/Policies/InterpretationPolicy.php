<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class InterpretationPolicy
{
    use HandlesAuthorization;

    public function create(User $user): Response
    {
        return Response::deny(__('You cannot manually create interpretations.'));
    }
}
