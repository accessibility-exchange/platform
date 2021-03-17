<?php

namespace App\Mail;

use App\Models\OrganizationInvitation as OrganizationInvitationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class OrganizationInvitation extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * The invitation.
     *
     * @var \App\Models\OrganizationInvitation
     */
    protected $invitation;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\OrganizationInvitation  $invitation
     * @return void
     */
    public function __construct(OrganizationInvitationModel $invitation)
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
            'mail.organization-invitation',
            [
                'acceptUrl' => URL::signedRoute('organization-invitations.accept', ['invitation' => $this->invitation]),
                'invitation' => $this->invitation,
            ]
        )->subject(__('Organization Invitation'));
    }
}
