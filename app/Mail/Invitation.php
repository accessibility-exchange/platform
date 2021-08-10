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

    /**
     * The invitation.
     *
     * @var \App\Models\Invitation
     */
    protected $invitation;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Invitation  $invitation
     * @return void
     */
    public function __construct(InvitationModel $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
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
