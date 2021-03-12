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
     * @param  int  $organizationUserId
     * @param  string  $role
     * @return void
     */
    public function update($user, $organization, $userId, string $role)
    {
        Gate::forUser($user)->authorize('update', $organization);

        Validator::make([
            'role' => $role,
        ], [
            'role' => ['required', 'string', Rule::in(['member', 'admin'])],
        ])->validate();

        $organization->users()->updateExistingPivot($userId, [
            'role' => $role,
        ]);

        flash(__('organization.role_update_succeeded', [
            'user' => User::where('id', $userId)->first()->name
        ]), 'success');
    }
}
