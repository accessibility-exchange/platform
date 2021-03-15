<?php

namespace App\Actions;

use App\Models\OrganizationUser;
use App\Models\User;
use App\Rules\NotLastAdmin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateOrganizationUserRole
{
    /**
     * Update the role for the given organization user.
     *
     * @param  mixed  $user
     * @param  mixed  $organization
     * @param  mixed  $member
     * @param  string  $role
     * @return void
     */
    public function update($user, $organization, $member, string $role)
    {
        Gate::forUser($user)->authorize('update', $organization);

        $validator = Validator::make(
            [
                'role' => $role,
                'organization_user' => OrganizationUser::where('organization_id', $organization->id)
                    ->where('user_id', $user->id)->first()
            ],
            [
                'role' => [
                    'required',
                    'string',
                    Rule::in(config('roles'))
                ],
            ]
        );

        $validator->sometimes(
            'organization_user',
            [new NotLastAdmin()],
            function ($input) {
                return $input->role != 'admin';
            }
        );

        $validator->validate();

        $organization->users()->updateExistingPivot($member->id, [
            'role' => $role,
        ]);

        flash(__('organization.role_update_succeeded', [
            'user' => $member->name
        ]), 'success');
    }
}
