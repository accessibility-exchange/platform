<?php

namespace App\Mail;

use App\Enums\IndividualRole;
use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ContractorInvitation extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected Invitation $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function build(): ContractorInvitation
    {
        return $this->markdown(
            'mail.contractor-invitation',
            [
                'acceptUrl' => URL::signedRoute('contractor-invitations.accept', $this->invitation),
                'invitation' => $this->invitation,
            ]
        )->subject(__(':role Invitation', ['role' => IndividualRole::labels()[$this->invitation->role]]));
    }
}
