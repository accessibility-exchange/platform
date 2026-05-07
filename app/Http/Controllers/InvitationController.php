<?php

namespace App\Http\Controllers;

use App\Enums\TeamRole;
use App\Http\Requests\AcceptInvitationRequest;
use App\Http\Requests\DeclineInvitationRequest;
use App\Http\Requests\StoreInvitationRequest;
use App\Mail\Invitation as InvitationMessage;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Notifications\NewMemberJoined;
use App\Traits\RetrievesUserByNormalizedEmail;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class InvitationController extends Controller
{
    use RetrievesUserByNormalizedEmail;

    public function create(StoreInvitationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $invitationable = $request->input('invitationable_type')::where('id', $request->input('invitationable_id'))->first();

        $invitation = $invitationable->invitations()->create($validated);

        Mail::to($validated['email'])->send(new InvitationMessage($invitation));

        flash(__('invitation.create_invitation_succeeded'), 'success|'.__('invitation.create_invitation_succeeded', [], 'en'));

        return redirect(localized_route('settings.edit-roles-and-permissions'));
    }

    public function accept(AcceptInvitationRequest $request, Invitation $invitation): RedirectResponse
    {
        $validated = $request->validated();
        $invitationable = $invitation->invitationable;
        $role = $invitation->role;
        $inviteeEmail = $invitation->email;

        $invitation->accept();

        if ($invitationable instanceof Organization || $invitationable instanceof RegulatedOrganization) {
            $invitee = $this->retrieveUserByEmail($inviteeEmail);

            $admins = User::whereAdministrator()->get();
            /** @var Organization|RegulatedOrganization $invitationable */
            Notification::send($admins, new NewMemberJoined(
                memberName: $invitee->name,
                account: $invitationable,
                teamRole: TeamRole::from($role),
            ));
        }

        flash(
            __('invitation.accept_invitation_succeeded', ['invitationable' => $invitation->invitationable->getTranslation('name', locale())]),
            'success|'.__('You have joined the team.', [], 'en')
        );

        return redirect(localized_route('dashboard'));
    }

    public function decline(DeclineInvitationRequest $request, Invitation $invitation): RedirectResponse
    {
        $validated = $request->validated();

        $invitation->delete();

        flash(
            __('invitation.decline_invitation_succeeded', ['invitationable' => $invitation->invitationable->getTranslation('name', locale())]),
            'success|'.__('invitation.decline_invitation_succeeded', [], 'en')
        );

        return redirect(localized_route('dashboard'));
    }

    public function destroy(Request $request, Invitation $invitation): RedirectResponse
    {
        if (! Gate::forUser($request->user())->check('update', $invitation->invitationable)) {
            throw new AuthorizationException;
        }

        $invitation->delete();

        flash(__('invitation.cancel_invitation_succeeded'), 'success|'.__('invitation.cancel_invitation_succeeded', [], 'en'));

        return redirect(localized_route('settings.edit-roles-and-permissions'));
    }
}
