<?php

namespace App\Notifications;

use App\Models\Organization;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;

class OrganizationPageNeedsUpdate extends PlatformNotification
{
    public Organization $organization;

    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
    }

    public function toMail(Organization $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Please review your page'))
            ->line(__('Please review your page. There is some information for your new role that you will have to fill in.'))
            ->action(__('Edit my organization’s page'), localized_route('organizations.edit', $this->organization));
    }

    public function toVonage(Organization $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content(
                __('Please review your page. There is some information for your new role that you will have to fill in.').' '.__(
                    'Edit my organization’s page: :url.',
                    [
                        'url' => localized_route('organizations.edit', $this->organization),
                    ]
                )
            )
            ->unicode();
    }

    public function toArray(Organization $notifiable): array
    {
        return [
            'organization_id' => $this->organization->id,
        ];
    }
}
