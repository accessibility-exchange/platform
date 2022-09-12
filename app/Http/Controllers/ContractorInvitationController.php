<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptContractorInvitationRequest;
use App\Http\Requests\StoreContractorInvitationRequest;
use App\Mail\ContractorInvitation;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContractorInvitationController extends Controller
{
    public function create(StoreContractorInvitationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $invitationable = $request->input('invitationable_type')::where('id', $request->input('invitationable_id'))->first();

        $invitation = $invitationable->invitations()->create($validated);

        Mail::to($validated['email'])->send(new ContractorInvitation($invitation));

        flash(__('invitation.create_invitation_succeeded'), 'success');

        $redirect = match ($request->input('invitationable_type')) {
            'App\Models\Project' => localized_route('projects.manage-'.$invitation->role, $invitationable),
            default => localized_route('engagements.manage-'.$invitation->role, $invitationable)
        };

        return redirect($redirect);
    }

    public function accept(AcceptContractorInvitationRequest $request, Invitation $invitation): RedirectResponse
    {
        $validated = $request->validated();

        $invitation->accept();

        flash(
            __('You have joined :invitationable as a :role', ['invitationable' => $invitation->invitationable->name, 'role' => $invitation->role]),
            'success'
        );

        return redirect(localized_route('dashboard'));
    }

    public function decline(Request $request, Invitation $invitation): RedirectResponse
    {
        if ($request->user()->email !== $invitation->email) {
            abort(403);
        }

        $invitation->delete();

        flash(
            __('You have declined your invitation to work as a :role on :invitationable.', ['role' => $invitation->role, 'invitationable' => $invitation->invitationable->name]),
            'success'
        );

        return redirect(localized_route('dashboard'));
    }

    public function destroy(Request $request, Invitation $invitation): RedirectResponse
    {
        if ($request->user()->cannot('update', $invitation->invitationable)) {
            abort(403);
        }

        $redirect = match ($request->input('invitationable_type')) {
            'App\Models\Project' => localized_route('projects.manage-'.$invitation->role, $invitation->invitationable),
            default => localized_route('engagements.manage-'.$invitation->role, $invitation->invitationable)
        };

        $invitation->delete();

        flash(__('invitation.cancel_invitation_succeeded'), 'success');

        return redirect($redirect);
    }
}
