<?php

namespace App\Notifications;

use App\Models\Engagement;
use App\Models\Organization;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;

class OrganizationAddedToEngagement extends PlatformNotification
{
    public Engagement $engagement;

    public function __construct(Engagement $engagement)
    {
        $this->engagement = $engagement;
    }

    public function toMail(Organization $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your organization has been added to an engagement', ['engagement' => $this->engagement->getTranslation('name', locale())]))
            ->line(__('Your organization has been added to the engagement “:engagement”.', ['engagement' => $this->engagement->getTranslation('name', locale())]))
            ->action(__('Visit engagement'), localized_route('engagements.manage-participants', $this->engagement));
    }

    public function toVonage(Organization $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content(
                __('Your organization has been added to the engagement “:engagement”.', ['engagement' => $this->engagement->getTranslation('name', locale())]).' '.__(
                    'Visit engagement: :url.',
                    [
                        'url' => localized_route('engagements.show', $this->engagement),
                    ]
                )
            )
            ->unicode();
    }

    public function toArray(Organization $notifiable): array
    {
        return [
            'engagement_id' => $this->engagement->id,
        ];
    }
}
