<?php

namespace App\Policies;

use App\Models\CommunityMember;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CommunityMemberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User  $user
     * @param \App\Models\CommunityMember  $model
     *
     * @return mixed
     */
    public function view(User $user, CommunityMember $model)
    {
        if ($model->checkStatus('draft')) {
            return $user->id === $model->user_id
                ? Response::allow()
                : Response::deny(__('You cannot view this community member page.'));
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can view personal details of the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CommunityMember  $communityMember
     * @return mixed
     */
    public function viewPersonalDetails(User $user, CommunityMember $communityMember)
    {
        return $user->id === $communityMember->user_id
            ? Response::allow()
            : Response::deny(__('You cannot view this community member page.'));
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
        if ($user->context === 'community-member') {
            return $user->communityMember
                ? Response::deny(__('You already have a community member page.'))
                : Response::allow();
        }

        return Response::deny(__('You cannot create a community member page.'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CommunityMember  $communityMember
     * @return mixed
     */
    public function update(User $user, CommunityMember $communityMember)
    {
        return $user->id === $communityMember->user_id
            ? Response::allow()
            : Response::deny(__('You cannot edit this community member page.'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CommunityMember  $communityMember
     * @return mixed
     */
    public function delete(User $user, CommunityMember $communityMember)
    {
        return $user->id === $communityMember->user_id
            ? Response::allow()
            : Response::deny(__('You cannot delete this community member page.'));
    }
}
