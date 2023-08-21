<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use App\Traits\UserCanViewPublishedContent;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OrganizationPolicy
{
    use HandlesAuthorization;
    use UserCanViewPublishedContent;

    public function before(User $user, string $ability): null|Response
    {
        if ($user->checkStatus('suspended') && $ability !== 'view') {
            return Response::deny(__('Your account has been suspended. Because of that, you do not have access to this page. Please contact us if you need further assistance.'));
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canViewPublishedContent($user);
    }

    public function view(User $user, Organization $organization): Response
    {
        // User can't view organization which they have blocked.
        if ($organization->blockedBy($user)) {
            return Response::deny(__('You’ve blocked :organization. If you want to visit this page, you can :unblock and return to this page.', [
                'organization' => '<strong>'.$organization->getTranslation('name', locale()).'</strong>',
                'unblock' => '<a href="'.localized_route('block-list.show').'">'.__('unblock them').'</a>',
            ]));
        }

        // Previewable drafts can be viewed by their team members or platform administrators.
        if ($organization->checkStatus('draft')) {
            if ($organization->isPreviewable()) {
                return $user->isMemberOf($organization) || $user->isAdministrator()
                    ? Response::allow()
                    : Response::denyAsNotFound();
            }

            return Response::denyAsNotFound();
        }

        // Suspended users can view or preview their own organizations.
        if ($user->checkStatus('suspended') && $organization->isPreviewable()) {
            return $user->isMemberOf($organization)
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        // Catch-all rule for published organization pages.
        return $this->canViewPublishedContent($user)
            ? Response::allow()
            : Response::deny();
    }

    public function create(User $user): Response
    {
        return $user->context === 'organization' && $user->organizations->isEmpty()
            ? Response::allow()
            : Response::deny(__('You already belong to an organization, so you cannot create a new one.'));
    }

    public function update(User $user, Organization $organization): Response
    {
        return $user->isAdministratorOf($organization)
            ? Response::allow()
            : Response::deny(__('You cannot edit this organization.'));
    }

    public function publish(User $user, Organization $organization): Response
    {
        return $user->isAdministratorOf($organization) && $organization->isPublishable()
            ? Response::allow()
            : Response::deny(__('You cannot publish this organization.'));
    }

    public function unpublish(User $user, Organization $organization): Response
    {
        return $user->isAdministratorOf($organization)
            ? Response::allow()
            : Response::deny(__('You cannot unpublish this organization.'));
    }

    public function delete(User $user, Organization $organization): Response
    {
        return $user->isAdministratorOf($organization)
            ? Response::allow()
            : Response::deny(__('You cannot delete this organization.'));
    }
}
