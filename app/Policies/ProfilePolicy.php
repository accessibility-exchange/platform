<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User  $user
     * @param \App\Models\Profile  $model
     *
     * @return mixed
     */
    public function view(User $user, Profile $model)
    {
        if ($model->status === 'draft') {
            return $user->id === $model->user_id
                ? Response::allow()
                : Response::deny(__('You cannot view this consultant page.'));
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User  $user
     * @param \App\Models\Profile  $model
     *
     * @return mixed
     */
    public function viewDraft(User $user, Profile $model)
    {
        if ($model->status === 'draft') {
            return $user->id === $model->user_id
                ? Response::allow()
                : Response::deny(__('You cannot view this consultant page.'));
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->context === 'consultant') {
            return $user->profile
                ? Response::deny(__('You already have a consultant page.'))
                : Response::allow();
        }

        return Response::deny(__('You cannot create a consultant page.'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Profile  $profile
     * @return mixed
     */
    public function update(User $user, Profile $profile)
    {
        return $user->id === $profile->user_id
            ? Response::allow()
            : Response::deny(__('You cannot edit this consultant page.'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Profile  $profile
     * @return mixed
     */
    public function delete(User $user, Profile $profile)
    {
        return $user->id === $profile->user_id
            ? Response::allow()
            : Response::deny(__('You cannot delete this consultant page.'));
    }
}
