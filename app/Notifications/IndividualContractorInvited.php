<?php

namespace App\Notifications;

use App\Enums\IndividualRole;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class IndividualContractorInvited extends Notification
{
    use Queueable;

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
            ->subject(__(':role Invitation', ['role' => IndividualRole::labels()[$this->invitation->role]]))
            ->markdown(
                'mail.individual-contractor-invitation',
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
                    'You have been invited to the :invitationable_type ":invitationable" as a :role on The Accessibility Exchange. Sign in to your account at https://accessibilityexchange.ca to continue.',
                    [
                        'invitationable_type' => $this->invitationable->singular_name,
                        'invitationable' => $this->invitationable->getTranslation('name', locale()),
                        'role' => IndividualRole::labels()[$this->invitation->role],
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
