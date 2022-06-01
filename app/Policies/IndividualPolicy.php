<?php

namespace App\Policies;

use App\Models\Individual;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class IndividualPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Individual $model
     *
     * @return Response
     */
    public function view(User $user, Individual $model): Response
    {
        if ($model->checkStatus('draft')) {
            return $user->id === $model->user_id
                ? Response::allow()
                : Response::deny(__('You cannot view this individual page.'));
        }

        return $model->blockedBy($user)
            ? Response::deny(__('You’ve blocked :individual. If you want to visit this page, you can :unblock and return to this page.', [
                'individual' => '<strong>' . $model->name . '</strong>',
                'unblock' => '<a href="' . localized_route('block-list.show') . '">' . __('unblock them') . '</a>',
            ]))
            : Response::allow();
    }

    public function block(User $user, Individual $individual): Response
    {
        return $user->individual->id === $individual->id
            ? Response::deny(__('You cannot block yourself.'))
            : Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Individual $individual
     * @return Response
     */
    public function update(User $user, Individual $individual): Response
    {
        return $user->id === $individual->user_id
            ? Response::allow()
            : Response::deny(__('You cannot edit this individual page.'));
    }

    /**
     * Determine whether the user can publish the model.
     *
     * @param User $user
     * @param Individual $individual
     * @return Response
     */
    public function publish(User $user, Individual $individual): Response
    {
        return $user->id === $individual->user_id && $individual->isPublishable()
            ? Response::allow()
            : Response::deny(__('You cannot edit this individual page.'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Individual $individual
     * @return Response
     */
    public function delete(User $user, Individual $individual): Response
    {
        return $user->id === $individual->user_id
            ? Response::allow()
            : Response::deny(__('You cannot delete this individual page.'));
    }
}
