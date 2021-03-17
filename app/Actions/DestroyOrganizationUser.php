<?php

namespace App\Actions;

use App\Models\OrganizationUser;
use App\Models\User;
use App\Rules\NotLastAdmin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DestroyOrganizationUser
{
    /**
     * Update the role for the given organization user.
     *
     * @param  mixed  $user
     * @param  mixed  $organization
     * @param  mixed  $member
     * @return void
     */
    public function destroy($user, $organization, $member)
    {
        Gate::forUser($user)->authorize('update', $organization);

        $organizationUser = OrganizationUser::where('organization_id', $organization->id)
        ->where('user_id', $member->id)->first();

        $validator = Validator::make(
            [
                'organization_user' => $organizationUser
            ],
            []
        );

        $validator->sometimes(
            'organization_user',
            [new NotLastAdmin()],
            function ($input) {
                ray($input['organization_user']->role);
                return $input['organization_user']->role === 'admin';
            }
        );

        $validator->validate();

        $organization->users()->detach($member->id);

        flash(__('organization.remove_member_succeeded', [
            'user' => $member->name
        ]), 'success');
    }
}
