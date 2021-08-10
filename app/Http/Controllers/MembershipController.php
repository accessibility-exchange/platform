<?php

namespace App\Http\Controllers;

use App\Actions\DestroyMembership;
use App\Actions\UpdateMembership;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Show the form for editing the specified memberable member.
     *
     * @param  \App\Models\Membership  $membership
     * @return mixed
     */
    public function edit(Membership $membership)
    {
        $roles = [];

        foreach (config('hearth.organizations.roles') as $role) {
            $roles[$role] = __('roles.' . $role);
        }

        return view('memberships.edit', [
            'membership' => $membership,
            'user' => $membership->user,
            'memberable' => $membership->memberable(),
            'roles' => $roles,
        ]);
    }

    /**
     * Update the given member's role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Membership $membership)
    {
        app(UpdateMembership::class)->update(
            $request->user(),
            $membership,
            $request->input('role')
        );

        if ($request->user()->id === $membership->user->id && $request->input('role') !== 'admin') {
            return redirect(
                \localized_route($membership->memberable()->getRoutePrefix() . '.show', $membership->memberable())
            );
        }

        return redirect(
            \localized_route($membership->memberable()->getRoutePrefix() . '.edit', $membership->memberable())
        );
    }

    /**
     * Remove the given member from the organization.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Membership $membership)
    {
        app(DestroyMembership::class)->destroy(
            $request->user(),
            $membership
        );

        if ($request->user()->id === $membership->user->id) {
            return redirect(
                \localized_route($membership->memberable()->getRoutePrefix() . '.show', $membership->memberable())
            );
        }

        return redirect(
            \localized_route($membership->memberable()->getRoutePrefix() . '.edit', $membership->memberable())
        );
    }
}
