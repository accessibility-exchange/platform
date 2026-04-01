<?php

namespace App\Notifications;

use App\Enums\TeamRole;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use Illuminate\Notifications\Messages\MailMessage;

class NewMemberJoinedOrganization extends PlatformNotification
{
    public function __construct(
        public string $memberName,
        public Organization|RegulatedOrganization $organization,
        public TeamRole $teamRole,
    ) {}

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New member joined organization'))
            ->line(__('A new member has joined an organization on The Accessibility Exchange.'))
            ->action(__('Dashboard'), localized_route('dashboard'));
    }

    public function toArray(): array
    {
        return [
            'member_name' => $this->memberName,
            'organization_id' => $this->organization->id,
            'organization_type' => get_class($this->organization),
            'team_role' => $this->teamRole->value,
        ];
    }
}
