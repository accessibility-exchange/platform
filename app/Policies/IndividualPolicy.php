<?php

namespace App\Policies;

use App\Models\Individual;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Str;

class IndividualPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): null|Response
    {
        if ($user->isSuspended() && $ability !== 'view') {
            return Response::deny(Str::markdown(
                __('Your account has been suspended. Because of that, you do not have access to this page. Please contact us if you need further assistance.')
                .contact_information()
            ));
        }

        return null;
    }

    public function viewAny(User $user): Response
    {
        return
             $user->isAdministrator() || $user->individual || $user->organization || $user->regulated_organization
            ? Response::allow()
            : Response::deny();
    }

    public function view(User $user, Individual $model): Response
    {
        if (! $model->isConsultant() && ! $model->isConnector()) {
            return Response::denyAsNotFound();
        }

        if ($model->blockedBy($user)) {
            return Response::deny(__('You’ve blocked :individual. If you want to visit this page, you can :unblock and return to this page.', [
                'individual' => '<strong>'.$model->name.'</strong>',
                'unblock' => '<a href="'.localized_route('block-list.show').'">'.__('unblock them').'</a>',
            ]));
        }

        if ($model->checkStatus('draft')) {
            return ($user->id === $model->user_id || $user->isAdministrator()) && $model->isPublishable()
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        if ($user->isSuspended() && $user->id === $model->user_id && $model->isPublishable()) {
            return Response::allow();
        }

        return $user->isAdministrator() || $user->individual || $user->organization || $user->regulated_organization
            ? Response::allow()
            : Response::deny();
    }

    public function update(User $user, Individual $individual): Response
    {
        if ($user->id !== $individual->user_id) {
            return Response::deny(__('You cannot edit this individual page.'));
        }

        return ($individual->isConsultant() || $individual->isConnector())
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function publish(User $user, Individual $individual): Response
    {
        return $user->id === $individual->user_id && $individual->isPublishable()
            ? Response::allow()
            : Response::deny(__('You cannot publish this individual page.'));
    }

    public function unpublish(User $user, Individual $individual): Response
    {
        return $user->id === $individual->user_id
            ? Response::allow()
            : Response::deny(__('You cannot unpublish this individual page.'));
    }

    public function delete(User $user, Individual $individual): Response
    {
        return $user->id === $individual->user_id
            ? Response::allow()
            : Response::deny(__('You cannot delete this individual page.'));
    }

    public function block(User $user, Individual $individual): Response
    {
        if (! config('app.features.blocking')) {
            return Response::deny();
        }

        return $user->individual && $user->individual->id === $individual->id
            ? Response::deny(__('You cannot block yourself.'))
            : Response::allow();
    }
}
