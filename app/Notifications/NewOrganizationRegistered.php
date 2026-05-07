<?php

namespace App\Notifications;

use App\Enums\UserContext;
use App\Models\Organization;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrganizationRegistered extends PlatformNotification
{
    public function __construct(
        public string $creatorName,
        public Organization $organization,
        public UserContext $userContext,
    ) {}

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New organization registered'))
            ->line(__('A new organization has been registered on The Accessibility Exchange.'))
            ->action(__('Dashboard'), localized_route('dashboard'));
    }

    public function toArray(): array
    {
        return [
            'creator_name' => $this->creatorName,
            'organization_id' => $this->organization->id,
            'user_context' => $this->userContext->value,
        ];
    }
}
