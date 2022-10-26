<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Str;

class OrganizationPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): null|Response
    {
        if ($user->isAdministrator() && $ability === 'viewAny') {
            return Response::allow();
        }

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
             $user->individual || $user->organization || $user->regulated_organization
                ? Response::allow()
                : Response::deny();
    }

    public function create(User $user): Response
    {
        return $user->context === 'organization' && $user->organizations->isEmpty()
            ? Response::allow()
            : Response::deny(__('You already belong to an organization, so you cannot create a new one.'));
    }

    public function view(User $user, Organization $organization): Response
    {
        if ($organization->blockedBy($user)) {
            return Response::deny(__('You’ve blocked :organization. If you want to visit this page, you can :unblock and return to this page.', [
                'organization' => '<strong>'.$organization->getTranslation('name', locale()).'</strong>',
                'unblock' => '<a href="'.localized_route('block-list.show').'">'.__('unblock them').'</a>',
            ]));
        }

        if ($organization->checkStatus('draft')) {
            return ($user->isAdministratorOf($organization) || $user->isAdministrator()) && $organization->isPublishable()
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        if ($user->isSuspended() && $organization->isPublishable()) {
            return $user->isAdministratorOf($organization) || $user->isAdministrator()
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        return $user->individual || $user->organization || $user->regulated_organization
            ? Response::allow()
            : Response::deny();
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
