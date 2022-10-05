<?php

namespace App\Notifications;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Support\Facades\URL;

class ParticipantInvited extends PlatformNotification
{
    public Invitation $invitation;

    public mixed $invitationable;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
        $this->invitationable = $this->invitation->invitationable;
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Participant Invitation'))
            ->markdown(
                'mail.participant-invitation',
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
                    'Your organization has been invited to the engagement ":invitationable" as a participant on The Accessibility Exchange. Sign in to your account at https://accessibilityexchange.ca to continue.',
                    [
                        'invitationable' => $this->invitationable->getTranslation('name', locale()),
                    ]
                )
            )
            ->unicode();
    }

    public function toArray(User $notifiable): array
    {
        return [
            'acceptUrl' => URL::signedRoute('contractor-invitations.accept', $this->invitation),
            'invitation_id' => $this->invitation->id,
        ];
    }
}
