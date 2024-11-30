<?php

namespace App\Notifications;

use App\Models\Individual;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;

class IndividualPublicPageNeedsUpdate extends PlatformNotification
{
    public Individual $individual;

    public function __construct(Individual $individual)
    {
        $this->individual = $individual;
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Please review your page'))
            ->line(__('Please review your page. There is some information for your new role that you will have to fill in.'))
            ->action(__('Edit my public page'), localized_route('individuals.edit', $this->individual));
    }

    public function toVonage(User $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content(
                __('Please review your page. There is some information for your new role that you will have to fill in.').' '.__(
                    'Edit my public page: :url.',
                    [
                        'url' => localized_route('individuals.edit', $this->individual),
                    ]
                )
            )
            ->unicode();
    }

    public function toArray(User $notifiable): array
    {
        return [
            'individual_id' => $this->individual->id,
        ];
    }
}
