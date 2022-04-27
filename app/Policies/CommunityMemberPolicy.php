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
     * @param User $user
     * @param CommunityMember $model
     *
     * @return Response
     */
    public function view(User $user, CommunityMember $model): Response
    {
        if ($model->checkStatus('draft')) {
            return $user->id === $model->user_id
                ? Response::allow()
                : Response::deny(__('You cannot view this community member page.'));
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     *
     * @return Response
     */
    public function create(User $user): Response
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
     * @param User $user
     * @param CommunityMember $communityMember
     * @return Response
     */
    public function update(User $user, CommunityMember $communityMember): Response
    {
        return $user->id === $communityMember->user_id
            ? Response::allow()
            : Response::deny(__('You cannot edit this community member page.'));
    }

    /**
     * Determine whether the user can publish the model.
     *
     * @param User $user
     * @param CommunityMember $communityMember
     * @return Response
     */
    public function publish(User $user, CommunityMember $communityMember): Response
    {
        return $user->id === $communityMember->user_id && $communityMember->isPublishable()
            ? Response::allow()
            : Response::deny(__('You cannot edit this community member page.'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param CommunityMember $communityMember
     * @return Response
     */
    public function delete(User $user, CommunityMember $communityMember): Response
    {
        return $user->id === $communityMember->user_id
            ? Response::allow()
            : Response::deny(__('You cannot delete this community member page.'));
    }
}
