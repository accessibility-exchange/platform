<?php

namespace App\Policies;

use App\Models\ConsultantProfile;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConsultantProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConsultantProfile  $consultantProfile
     * @return mixed
     */
    public function view(User $user, ConsultantProfile $consultantProfile)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->consultantProfile
            ? Response::deny(__('You already have a consultant profile.'))
            : Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConsultantProfile  $consultantProfile
     * @return mixed
     */
    public function update(User $user, ConsultantProfile $consultantProfile)
    {
        return $user->id === $consultantProfile->user_id
            ? Response::allow()
            : Response::deny('You cannot edit this consultant profile.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConsultantProfile  $consultantProfile
     * @return mixed
     */
    public function delete(User $user, ConsultantProfile $consultantProfile)
    {
        return $user->id === $consultantProfile->user_id
            ? Response::allow()
            : Response::deny('You cannot delete this consultant profile.');
    }
}
