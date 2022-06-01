<?php

namespace App\Policies;

use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RegulatedOrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response
     */
    public function create(User $user): Response
    {
        return $user->context === 'regulated-organization' && ! $user->joinable && $user->regulatedOrganizations->isEmpty()
            ? Response::allow()
            : Response::deny(__('You already belong to an organization, so you cannot create a new one.'));
    }

    /**
     * Determine whether the user can join the model.
     *
     * @param User $user
     * @param RegulatedOrganization $regulatedOrganization
     * @return Response
     */
    public function join(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        return $user->context === 'regulated-organization' && ! $user->joinable && $user->regulatedOrganizations->isEmpty()
            ? Response::allow()
            : Response::deny(__('You cannot join this organization.'));
    }

    public function view(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        return $regulatedOrganization->blockedBy($user)
            ? Response::deny(__('You’ve blocked :regulatedOrganization. If you want to visit this page, you can :unblock and return to this page.', [
                'regulatedOrganization' => '<strong>' . $regulatedOrganization->getTranslation('name', locale()) . '</strong>',
                'unblock' => '<a href="' . localized_route('blocklist.show') . '">' . __('unblock them') . '</a>',
            ]))
            : Response::allow();
    }

    /**
     * Determine whether the user can update a federally regulated organization.
     *
     * @param User $user
     * @param RegulatedOrganization $regulatedOrganization
     * @return Response
     */
    public function update(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        return $user->isAdministratorOf($regulatedOrganization)
            ? Response::allow()
            : Response::deny(__('You cannot edit this federally regulated organization.'));
    }

    public function block(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        return $user->isMemberOf($regulatedOrganization)
            ? Response::deny(__('You cannot block the :type that you belong to.', ['type' => __('regulated-organization.types.' . $regulatedOrganization->type)]))
            : Response::allow();
    }

    /**
     * Determine whether the user can publish the model.
     *
     * @param User $user
     * @param RegulatedOrganization $regulatedOrganization
     * @return Response
     */
    public function publish(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        // TODO: Ensure model is ready for publishing first.
        return $user->isAdministratorOf($regulatedOrganization)
            ? Response::allow()
            : Response::deny(__('You cannot publish this regulated organization.'));
    }

    /**
     * Determine whether the user can delete a federally regulated organization.
     *
     * @param User $user
     * @param RegulatedOrganization $regulatedOrganization
     * @return Response
     */
    public function delete(User $user, RegulatedOrganization $regulatedOrganization): Response
    {
        return $user->isAdministratorOf($regulatedOrganization)
            ? Response::allow()
            : Response::deny(__('You cannot delete this federally regulated organization.'));
    }
}
