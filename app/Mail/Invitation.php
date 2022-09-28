<?php

namespace App\Mail;

use App\Models\Invitation as InvitationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class Invitation extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected InvitationModel $invitation;

    public function __construct(InvitationModel $invitation)
    {
        $this->invitation = $invitation;
    }

    public function build(): Invitation
    {
        return $this->markdown(
            'mail.invitation',
            [
                'acceptUrl' => URL::signedRoute('invitations.accept', $this->invitation),
                'invitation' => $this->invitation,
            ]
        )->subject(__('Team Invitation'));
    }
}
