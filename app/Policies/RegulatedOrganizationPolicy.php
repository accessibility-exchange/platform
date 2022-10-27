<?php

namespace App\Policies;

use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Traits\UserCanViewPublishedContent;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Str;

class RegulatedOrganizationPolicy
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

    public function viewAny(User $user): Response
    {
        return $this->canViewPublishedContent($user);
    }

    public function view(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        // User can't view organization which they have blocked.
        if ($regulatedOrganization->blockedBy($user)) {
            return Response::deny(__('You’ve blocked :regulatedOrganization. If you want to visit this page, you can :unblock and return to this page.', [
                'regulatedOrganization' => '<strong>'.$regulatedOrganization->getTranslation('name', locale()).'</strong>',
                'unblock' => '<a href="'.localized_route('block-list.show').'">'.__('unblock them').'</a>',
            ]));
        }

        // Previewable drafts can be viewed by their team members or platform administrators.
        if ($regulatedOrganization->checkStatus('draft')) {
            if ($regulatedOrganization->isPreviewable()) {
                return $user->isMemberOf($regulatedOrganization) || $user->isAdministrator()
                    ? Response::allow()
                    : Response::denyAsNotFound();
            }

            return Response::denyAsNotFound();
        }

        // Suspended individual users can view or preview their own regulated organizations.
        if ($user->isSuspended() && $regulatedOrganization->isPreviewable()) {
            return $user->isAdministratorOf($regulatedOrganization) || $user->isAdministrator()
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        // Catch-all rule for published regulated organization pages.
        return $this->canViewPublishedContent($user)
            ? Response::allow()
            : Response::deny();
    }

    public function create(User $user): Response
    {
        return $user->context === 'regulated-organization' && $user->regulatedOrganizations->isEmpty()
            ? Response::allow()
            : Response::deny(__('You already belong to an organization, so you cannot create a new one.'));
    }

    public function update(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        return $user->isAdministratorOf($regulatedOrganization)
            ? Response::allow()
            : Response::deny(__('You cannot edit this federally regulated organization.'));
    }

    public function publish(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        return $user->isAdministratorOf($regulatedOrganization) && $regulatedOrganization->isPublishable()
            ? Response::allow()
            : Response::deny(__('You cannot publish this regulated organization.'));
    }

    public function unpublish(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        return $user->isAdministratorOf($regulatedOrganization)
            ? Response::allow()
            : Response::deny(__('You cannot unpublish this regulated organization.'));
    }

    public function delete(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        return $user->isAdministratorOf($regulatedOrganization)
            ? Response::allow()
            : Response::deny(__('You cannot delete this federally regulated organization.'));
    }
}
