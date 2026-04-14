<?php

namespace App\Notifications;

use App\Enums\TeamRole;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use Illuminate\Notifications\Messages\MailMessage;

class NewMemberJoined extends PlatformNotification
{
    public function __construct(
        public string $memberName,
        public Organization|RegulatedOrganization $account,
        public TeamRole $teamRole,
    ) {}

    public function toMail(): MailMessage
    {
        $line = $this->account instanceof RegulatedOrganization
                ? __('A new member has joined a regulated organization on The Accessibility Exchange.')
                : __('A new member has joined an organization on The Accessibility Exchange.');

        return (new MailMessage)
            ->subject(__('New member joined'))
            ->line($line)
            ->action(__('Dashboard'), localized_route('dashboard'));
    }

    public function toArray(): array
    {
        return [
            'member_name' => $this->memberName,
            'account_id' => $this->account->id,
            'account_type' => get_class($this->account),
            'team_role' => $this->teamRole->value,
        ];
    }
}
