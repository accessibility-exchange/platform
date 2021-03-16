<?php

namespace App\Http\Controllers;

use App\Actions\AcceptOrganizationInvitation;
use App\Http\Requests\CreateOrganizationInvitationRequest;
use App\Mail\OrganizationInvitation as OrganizationInvitationMessage;
use App\Models\Organization;
use App\Models\OrganizationInvitation;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class OrganizationInvitationController extends Controller
{
    /**
     * Create an invitation.
     *
     * @param  \App\Http\Requests\CreateOrganizationInvitationRequest  $request
     * @param  \App\Models\OrganizationInvitation  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CreateOrganizationInvitationRequest $request, Organization $organization)
    {
        $validated = $request->validated();

        $invitation = $organization->organizationInvitations()->create($validated);

        Mail::to($validated['email'])->send(new OrganizationInvitationMessage($invitation));

        flash(__('organization.create_invitation_succeeded'), 'success');

        return redirect(localized_route('organizations.edit', $organization));
    }

    /**
     * Accept the specified invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrganizationInvitation  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Request $request, OrganizationInvitation $invitation)
    {
        app(AcceptOrganizationInvitation::class)->accept(
            $invitation->organization,
            $invitation->email,
            $invitation->role
        );

        $invitation->delete();

        flash(__('organization.accept_invitation_succeeded', ['organization' => $invitation->organization]), 'success');

        return redirect(localized_route('organizations.show', $invitation->organization));
    }

    /**
     * Cancel the specified invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrganizationInvitation  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, OrganizationInvitation $invitation)
    {
        if (!Gate::forUser($request->user())->check('update', $invitation->organization)) {
            throw new AuthorizationException();
        }

        $invitation->delete();

        flash(__('organization.cancel_invitation_succeeded'), 'success');

        return redirect(localized_route('organizations.edit', $invitation->organization));
    }
}
