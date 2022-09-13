<?php

namespace App\Notifications;

use App\Enums\OrganizationRole;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class OrganizationalContractorInvited extends Notification
{
    use Queueable;

    public Invitation $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function via(User $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(User $notifiable): MailMessage
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

    public function toArray(User $notifiable): array
    {
        return [
            'acceptUrl' => URL::signedRoute('contractor-invitations.accept', $this->invitation),
            'invitation_id' => $this->invitation->id,
        ];
    }
}
