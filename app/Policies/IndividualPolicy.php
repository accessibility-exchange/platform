<?php

namespace App\Policies;

use App\Models\Individual;
use App\Models\User;
use App\Traits\UserCanViewPublishedContent;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class IndividualPolicy
{
    use HandlesAuthorization, UserCanViewPublishedContent;

    public function before(User $user, string $ability): ?Response
    {
        if ($user->checkStatus('suspended') && $ability !== 'view') {
            return Response::deny(__('Your account has been suspended. Because of that, you do not have access to this page. Please contact us if you need further assistance.'));
        }

        return null;
    }

    public function viewAny(User $user): Response
    {
        return $this->canViewPublishedContent($user)
            ? Response::allow()
            : Response::deny();
    }

    public function view(User $user, Individual $individual): Response
    {
        // Participants don't have published pages.
        if (! $individual->isConsultant() && ! $individual->isConnector()) {
            return Response::denyAsNotFound();
        }

        // User can't view individual who they have blocked.
        if ($individual->blockedBy($user)) {
            return Response::deny(__('You’ve blocked :individual. If you want to visit this page, you can :unblock and return to this page.', [
                'individual' => '<strong>'.$individual->name.'</strong>',
                'unblock' => '<a href="'.localized_route('block-list.show').'">'.__('unblock them').'</a>',
            ]));
        }

        // Previewable drafts can be viewed by their owners or platform administrators.
        if ($individual->checkStatus('draft')) {
            if ($individual->isPreviewable()) {
                return $user->id === $individual->user_id || $user->isAdministrator()
                    ? Response::allow()
                    : Response::denyAsNotFound();
            }

            return Response::denyAsNotFound();
        }

        // Suspended users can view or preview their own individual pages.
        if ($user->checkStatus('suspended') && $individual->isPreviewable()) {
            return $user->id === $individual->user_id
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        // Catch-all rule for published individual pages.
        return $this->canViewPublishedContent($user)
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
