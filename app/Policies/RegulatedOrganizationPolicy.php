<?php

namespace App\Policies;

use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RegulatedOrganizationPolicy
{
    use HandlesAuthorization;

    public function before(User $user): null|bool
    {
        return $user->isAdministrator() ? true : null;
    }

    public function create(User $user): Response
    {
        return $user->context === 'regulated-organization' && $user->regulatedOrganizations->isEmpty()
            ? Response::allow()
            : Response::deny(__('You already belong to an organization, so you cannot create a new one.'));
    }

    public function viewAny(User $user): Response
    {
        return
             $user->individual || $user->organization || $user->regulated_organization
                ? Response::allow()
                : Response::deny();
    }

    public function view(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        if ($regulatedOrganization->blockedBy($user)) {
            return Response::deny(__('You’ve blocked :regulatedOrganization. If you want to visit this page, you can :unblock and return to this page.', [
                'regulatedOrganization' => '<strong>'.$regulatedOrganization->getTranslation('name', locale()).'</strong>',
                'unblock' => '<a href="'.localized_route('block-list.show').'">'.__('unblock them').'</a>',
            ]));
        }

        return $user->individual || $user->organization || $user->regulated_organization
            ? Response::allow()
            : Response::deny();
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
