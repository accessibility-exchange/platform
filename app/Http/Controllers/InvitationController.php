<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptInvitationRequest;
use App\Http\Requests\StoreInvitationRequest;
use App\Mail\Invitation as InvitationMessage;
use Hearth\Models\Invitation;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    /**
     * Create an invitation.
     *
     * @param  StoreInvitationRequest  $request
     * @return RedirectResponse
     */
    public function create(StoreInvitationRequest $request)
    {
        $validated = $request->validated();

        $invitationable = $request->input('invitationable_type')::where('id', $request->input('invitationable_id'))->first();

        $invitation = $invitationable->invitations()->create($validated);

        Mail::to($validated['email'])->send(new InvitationMessage($invitation));

        flash(__('invitation.create_invitation_succeeded'), 'success');

        return redirect(localized_route('users.edit-roles-and-permissions'));
    }

    /**
     * Accept the specified invitation.
     *
     * @param  AcceptInvitationRequest  $request
     * @param  Invitation  $invitation
     * @return RedirectResponse
     */
    public function accept(AcceptInvitationRequest $request, Invitation $invitation)
    {
        $validated = $request->validated();

        $invitation->accept();

        flash(
            __('invitation.accept_invitation_succeeded', ['invitationable' => $invitation->invitationable->name]),
            'success'
        );

        return redirect(localized_route($invitation->invitationable->getRoutePrefix().'.show', $invitation->invitationable));
    }

    /**
     * Cancel the specified invitation.
     *
     * @param  Request  $request
     * @param  Invitation  $invitation
     * @return RedirectResponse
     */
    public function destroy(Request $request, Invitation $invitation)
    {
        if (! Gate::forUser($request->user())->check('update', $invitation->invitationable)) {
            throw new AuthorizationException();
        }

        $invitation->delete();

        flash(__('invitation.cancel_invitation_succeeded'), 'success');

        return redirect(localized_route('users.edit-roles-and-permissions'));
    }
}
