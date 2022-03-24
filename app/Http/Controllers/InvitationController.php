<?php

namespace App\Http\Controllers;

use App\Actions\AcceptInvitation;
use App\Http\Requests\StoreInvitationRequest;
use App\Mail\Invitation as InvitationMessage;
use App\Models\Invitation;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    /**
     * Create an invitation.
     *
     * @param  \App\Http\Requests\StoreInvitationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(StoreInvitationRequest $request)
    {
        $validated = $request->validated();

        $inviteable = $request->input('inviteable_type')::where('id', $request->input('inviteable_id'))->first();

        $invitation = $inviteable->invitations()->create($validated);

        Mail::to($validated['email'])->send(new InvitationMessage($invitation));

        flash(__('invitation.create_invitation_succeeded'), 'success');


        return redirect(\localized_route($inviteable->getRoutePrefix() . '.edit', $inviteable));
    }

    /**
     * Accept the specified invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invitation  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Request $request, Invitation $invitation)
    {
        app(AcceptInvitation::class)->accept(
            $invitation->inviteable,
            $invitation->email,
            $invitation->role
        );

        $invitation->delete();

        flash(
            __('invitation.accept_invitation_succeeded', ['inviteable' => $invitation->inviteable->name]),
            'success'
        );


        return redirect(\localized_route($invitation->inviteable->getRoutePrefix() . '.show', $invitation->inviteable));
    }

    /**
     * Cancel the specified invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invitation  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Invitation $invitation)
    {
        if (! Gate::forUser($request->user())->check('update', $invitation->inviteable)) {
            throw new AuthorizationException();
        }

        $invitation->delete();

        flash(__('invitation.cancel_invitation_succeeded'), 'success');


        return redirect(\localized_route($invitation->inviteable->getRoutePrefix() . '.edit', $invitation->inviteable));
    }
}
