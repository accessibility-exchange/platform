<?php

namespace App\Policies;

use App\Models\Story;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class StoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Story  $story
     * @return mixed
     */
    public function update(User $user, Story $story)
    {
        return $user->id === $story->user_id
            ? Response::allow()
            : Response::deny('You cannot edit this story.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Story  $story
     * @return mixed
     */
    public function delete(User $user, Story $story)
    {
        return $user->id === $story->user_id
            ? Response::allow()
            : Response::deny('You cannot delete this story.');
    }
}
