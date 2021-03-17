<?php

namespace App\Actions;

use App\Models\Membership;
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

        $membership = Membership::where('membership_id', $organization->id)
            ->where('membership_type', 'organization')
            ->where('user_id', $member->id)
            ->first();

        $validator = Validator::make(
            [
                'membership' => $membership
            ],
            []
        );

        $validator->sometimes(
            'membership',
            [new NotLastAdmin()],
            function ($input) {
                return $input['membership']->role === 'admin';
            }
        );

        $validator->validate();

        $organization->users()->detach($member->id);

        flash(__('organization.remove_member_succeeded', [
            'user' => $member->name
        ]), 'success');
    }
}
