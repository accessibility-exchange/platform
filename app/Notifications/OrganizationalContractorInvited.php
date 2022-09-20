<?php

namespace App\Notifications;

use App\Enums\OrganizationRole;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Support\Facades\URL;

class OrganizationalContractorInvited extends PlatformNotification
{
    public Invitation $invitation;

    public mixed $invitationable;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
        $this->invitationable = $this->invitation->invitationable;
    }

    public function toMail(Organization $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__(':role Invitation', ['role' => OrganizationRole::labels()[$this->invitation->role]]))
            ->markdown(
                'mail.organizational-contractor-invitation',
                [
                    'acceptUrl' => URL::signedRoute('contractor-invitations.accept', $this->invitation),
                    'invitation' => $this->invitation,
                ]
            );
    }

    public function toVonage(User $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content(
                __(
                    'Your organization has been invited to the :invitationable_type ":invitationable" as a :role on The Accessibility Exchange. Sign in to your account at https://accessibilityexchange.ca to continue.',
                    [
                        'invitationable_type' => $this->invitationable->singular_name,
                        'invitationable' => $this->invitationable->getTranslation('name', locale()),
                        'role' => OrganizationRole::labels()[$this->invitation->role],
                    ]
                )
            )
            ->unicode();
    }

    public function toArray(Organization $notifiable): array
    {
        return [
            'acceptUrl' => URL::signedRoute('contractor-invitations.accept', $this->invitation),
            'invitation_id' => $this->invitation->id,
        ];
    }
}
