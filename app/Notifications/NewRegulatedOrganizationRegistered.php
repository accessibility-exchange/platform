<?php

namespace App\Notifications;

use App\Enums\UserContext;
use App\Models\RegulatedOrganization;
use Illuminate\Notifications\Messages\MailMessage;

class NewRegulatedOrganizationRegistered extends PlatformNotification
{
    public function __construct(
        public string $creatorName,
        public RegulatedOrganization $regulatedOrganization,
        public UserContext $userContext,
    ) {}

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New regulated organization registered'))
            ->line(__('A new regulated organization has been registered on The Accessibility Exchange.'))
            ->action(__('Dashboard'), localized_route('dashboard'));
    }

    public function toArray(): array
    {
        return [
            'creator_name' => $this->creatorName,
            'regulated_organization_id' => $this->regulatedOrganization->id,
            'user_context' => $this->userContext->value,
        ];
    }
}
