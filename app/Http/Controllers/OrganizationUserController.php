<?php

namespace App\Http\Controllers;

use App\Actions\UpdateOrganizationUserRole;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizationUserController extends Controller
{
    /**
     * Show the form for editing the specified organization member.
     *
     * @param  \App\Models\Organization  $organization
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function edit(Organization $organization, User $user)
    {
        if (!$user->isMemberOf($organization)) {
            return redirect(localized_route('organizations.edit', $organization));
        }

        $roles = [];

        foreach (config('roles') as $role) {
            $roles[$role] = __('roles.' . $role);
        }

        return view('organizations.user-edit', ['organization' => $organization, 'user' => $user, 'roles' => $roles]);
    }

    /**
     * Update the given team member's role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Organization  $organization
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Organization $organization, User $user)
    {
        app(UpdateOrganizationUserRole::class)->update(
            $request->user(),
            $organization,
            $user,
            $request->input('role')
        );

        return redirect(localized_route('organizations.edit', $organization));
    }
}
