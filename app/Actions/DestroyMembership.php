<?php

namespace App\Actions;

use App\Models\Membership;
use App\Rules\NotLastAdmin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class DestroyMembership
{
    /**
     * Destroy the given membership.
     *
     * @param  mixed  $user
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function destroy($user, Membership $membership)
    {
        Gate::forUser($user)->authorize('update', $membership->memberable());

        $validator = Validator::make(
            [
                'membership' => $membership,
            ],
            []
        );

        $validator->sometimes(
            'membership',
            [new NotLastAdmin()],
            function ($input) {
                return $input->membership->role === 'admin';
            }
        );

        $validator->validate();

        $membership->memberable()->users()->detach($membership->user->id);

        flash(__('membership.remove_member_succeeded', [
            'user' => $membership->user->name,
            'memberable' => $membership->memberable()->name,
        ]), 'success');
    }
}
