<?php

namespace App\Http\Controllers;

use App\Actions\UpdateOrganizationUserRole;
use App\Models\Organization;
use App\Models\OrganizationUser;
use Illuminate\Http\Request;

class OrganizationUserController extends Controller
{
     /**
     * Update the given team member's role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Organization $organization)
    {
        app(UpdateOrganizationUserRole::class)->update(
            $request->user(),
            $organization,
            $request->input('userId'),
            $request->input('role')
        );

        return back(303);
    }
}
