<?php

namespace App\Policies;

use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RegulatedOrganizationPolicy
{
    use HandlesAuthorization;

    public function create(User $user): Response
    {
        return $user->context === 'regulated-organization' && $user->regulatedOrganizations->isEmpty()
            ? Response::allow()
            : Response::deny(__('You already belong to an organization, so you cannot create a new one.'));
    }

    public function view(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        return $regulatedOrganization->blockedBy($user)
            ? Response::deny(__('You’ve blocked :regulatedOrganization. If you want to visit this page, you can :unblock and return to this page.', [
                'regulatedOrganization' => '<strong>'.$regulatedOrganization->getTranslation('name', locale()).'</strong>',
                'unblock' => '<a href="'.localized_route('block-list.show').'">'.__('unblock them').'</a>',
            ]))
            : Response::allow();
    }

    public function update(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        return $user->isAdministratorOf($regulatedOrganization)
            ? Response::allow()
            : Response::deny(__('You cannot edit this federally regulated organization.'));
    }

    public function publish(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        // TODO: Ensure model is ready for publishing first.
        return $user->isAdministratorOf($regulatedOrganization)
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
