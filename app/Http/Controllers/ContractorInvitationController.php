<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class ContractorInvitationController extends Controller
{
    public function accept(Request $request, Invitation $invitation): RedirectResponse
    {
        if (
            $invitation->type === 'individual' && $request->user()->email !== $invitation->email
            || $invitation->type === 'organization' && $request->user()->organization?->contact_person_email !== $invitation->email
        ) {
            abort(403);
        }

        $invitation->accept($invitation->type);

        $notifications = DatabaseNotification::where('data->invitation_id', $invitation->id)->get();

        foreach ($notifications as $notification) {
            $notification->delete();
        }

        flash(
            __('You have joined :invitationable as a :role', ['invitationable' => $invitation->invitationable->name, 'role' => $invitation->role]),
            'success'
        );

        return redirect(localized_route('dashboard'));
    }

    public function decline(Request $request, Invitation $invitation): RedirectResponse
    {
        if (
            $invitation->type === 'individual' && $request->user()->email !== $invitation->email
            || $invitation->type === 'organization' && $request->user()->organization?->contact_person_email !== $invitation->email
        ) {
            abort(403);
        }

        $invitation->delete();

        $notifications = DatabaseNotification::where('data->invitation_id', $invitation->id)->get();

        foreach ($notifications as $notification) {
            $notification->delete();
        }

        flash(
            $invitation->type === 'individual'
                ? __('You have declined your invitation to work as a :role on :invitationable.', [
                    'role' => $invitation->role,
                    'invitationable' => $invitation->invitationable->name,
                ])
                : __('You have declined an invitation on behalf of your organization, :organization, to work as a :role on :invitationable.', [
                    'organization' => $request->user()->organization->name,
                    'role' => $invitation->role,
                    'invitationable' => $invitation->invitationable->name,
                ]),
            'success'
        );

        return redirect(localized_route('dashboard'));
    }
}
