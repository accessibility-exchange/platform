<?php

namespace App\Policies;

use App\Models\Engagement;
use App\Models\User;
use App\Traits\UserCanViewPublishedContent;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Str;

class EngagementPolicy
{
    use HandlesAuthorization;
    use UserCanViewPublishedContent;

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

    public function view(User $user, Engagement $engagement): Response
    {
        // User can't view engagement by organization or regulated organization which they have blocked.
        if ($engagement->project->projectable->blockedBy($user)) {
            return Response::deny(__('You’ve blocked :organization. If you want to visit this page, you can :unblock and return to this page.', [
                'organization' => '<strong>'.$engagement->project->projectable->getTranslation('name', locale()).'</strong>',
                'unblock' => '<a href="'.localized_route('block-list.show').'">'.__('unblock them').'</a>',
            ]));
        }

        // Previewable drafts can be viewed by their team members or platform administrators.
        if ($engagement->checkStatus('draft')) {
            if ($engagement->isPreviewable()) {
                return $user->isMemberOf($engagement->project->projectable) || $user->isAdministrator()
                    ? Response::allow()
                    : Response::denyAsNotFound();
            }

            return Response::denyAsNotFound();
        }

        // Suspended users can view or preview their own engagements.
        if ($user->isSuspended() && $engagement->isPublishable()) {
            return $user->isAdministratorOf($engagement->project->projectable)
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        // Catch-all rule for published project pages.
        return $this->canViewPublishedContent($user)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function update(User $user, Engagement $engagement): Response
    {
        return $user->can('update', $engagement->project)
            ? Response::allow()
            : Response::deny();
    }

    public function addConnector(User $user, Engagement $engagement): Response
    {
        return
            $user->can('update', $engagement)
            && ! $engagement->connector
            && ! $engagement->organizationalConnector
            && ! $engagement->invitations->where('role', 'connector')->count()
                ? Response::allow()
                : Response::deny();
    }

    public function viewParticipants(User $user, Engagement $engagement): Response
    {
        if ($user->isAdministratorOf($engagement->project->projectable)) {
            return Response::allow();
        }

        if ($engagement->organizationalConnector && $user->isAdministratorOf($engagement->organizationalConnector)) {
            return Response::allow();
        }

        if ($engagement->connector && $engagement->connector->id === $user->individual?->id) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function manageParticipants(User $user, Engagement $engagement): Response
    {
        if ($engagement->organizationalConnector && $user->isAdministratorOf($engagement->organizationalConnector)) {
            return Response::allow();
        }

        if ($engagement->connector && $engagement->connector->id === $user->individual?->id) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function addParticipants(User $user, Engagement $engagement): Response
    {
        $attachedOrInvitedParticipants = $engagement->invitations->where('role', 'participant')->count() + $engagement->confirmedParticipants->count();

        return $user->can('manageParticipants', $engagement) && $attachedOrInvitedParticipants < $engagement->ideal_participants
            ? Response::allow()
            : Response::deny();
    }

    public function manageOrganization(User $user, Engagement $engagement): Response
    {
        return $user->isAdministratorOf($engagement->project->projectable) && $engagement->who === 'organization'
            ? Response::allow()
            : Response::deny();
    }

    public function addOrganization(User $user, Engagement $engagement): Response
    {
        return $user->isAdministratorOf($engagement->project->projectable) && $engagement->who === 'organization' && ! $engagement->organization
            ? Response::allow()
            : Response::deny();
    }

    public function removeOrganization(User $user, Engagement $engagement): Response
    {
        return $user->isAdministratorOf($engagement->project->projectable) && $engagement->who === 'organization' && $engagement->organization
            ? Response::allow()
            : Response::deny();
    }

    public function join(User $user, Engagement $engagement): Response
    {
        return $engagement->recruitment === 'open-call'
            && $user->individual?->isParticipant()
            && $engagement->signup_by_date > now()
            && ! $engagement->confirmedParticipants->contains($user->individual)
            && $engagement->confirmedParticipants->count() < $engagement->ideal_participants
                ? Response::allow()
                : Response::deny();
    }

    public function participate(User $user, Engagement $engagement): Response
    {
        return $engagement->confirmedParticipants->contains($user->individual)
            ? Response::allow()
            : Response::deny();
    }

    public function leave(User $user, Engagement $engagement): Response
    {
        return $engagement->recruitment === 'open-call'
            && $engagement->confirmedParticipants->contains($user->individual)
            && $engagement->signup_by_date > now()
            ? Response::allow()
            : Response::deny();
    }
}
