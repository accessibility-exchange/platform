<?php

namespace App\Notifications;

use App\Models\Engagement;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;

class AccessNeedsFacilitationRequested extends PlatformNotification
{
    public User $user;

    public Engagement $engagement;

    public function __construct(User $user, Engagement $engagement)
    {
        $this->user = $user;
        $this->engagement = $engagement;
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__(':name requires access needs facilitation', ['name' => $this->user->name]))
            ->markdown(
                'mail.access-needs-facilitation-requested',
                [
                    'individual' => $this->user->individual,
                    'engagement' => $this->engagement,
                ]
            );
    }

    public function toArray(): array
    {
        return [
            'individual_id' => $this->user->individual->id,
            'engagement_id' => $this->engagement->id,
        ];
    }
}
