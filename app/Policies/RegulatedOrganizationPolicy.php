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
     * Determine whether the user can create federally regulated organizations.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->context === 'regulated-organization';
    }

    /**
     * Determine whether the user can create a project for an federally regulated organization.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RegulatedOrganization  $projectable
     * @return mixed
     */
    public function createProject(User $user, RegulatedOrganization $projectable)
    {
        return $user->isAdministratorOf($projectable)
            ? Response::allow()
            : Response::deny(__('You cannot create a project for this federally regulated organization.'));
    }

    /**
     * Determine whether the user can update an federally regulated organization.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RegulatedOrganization  $regulatedOrganization
     * @return mixed
     */
    public function update(User $user, RegulatedOrganization $regulatedOrganization)
    {
        return $user->isAdministratorOf($regulatedOrganization)
            ? Response::allow()
            : Response::deny(__('You cannot edit this federally regulated organization.'));
    }

    /**
     * Determine whether the user can delete an federally regulated organization.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RegulatedOrganization  $regulatedOrganization
     * @return mixed
     */
    public function delete(User $user, RegulatedOrganization $regulatedOrganization)
    {
        return $user->isAdministratorOf($regulatedOrganization)
            ? Response::allow()
            : Response::deny(__('You cannot delete this federally regulated organization.'));
    }
}
