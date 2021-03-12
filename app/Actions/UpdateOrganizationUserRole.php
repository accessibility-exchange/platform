<?php

namespace App\Actions;

use App\Models\User;
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

        Validator::make([
            'role' => $role,
        ], [
            'role' => ['required', 'string', Rule::in(config('roles'))],
        ])->validate();

        $organization->users()->updateExistingPivot($member->id, [
            'role' => $role,
        ]);

        flash(__('organization.role_update_succeeded', [
            'user' => $member->name
        ]), 'success');
    }
}
