<?php

namespace App\Notifications;

use App\Enums\UserContext;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use Illuminate\Notifications\Messages\MailMessage;

class UserAccountDeleted extends PlatformNotification
{
    public function __construct(
        public string $userName,
        public UserContext $userContext,
        public Organization|RegulatedOrganization|null $account = null,
    ) {}

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('User account deleted'))
            ->line(__('A user has deleted their account on The Accessibility Exchange.'))
            ->action(__('Dashboard'), localized_route('dashboard'));
    }

    public function toArray(): array
    {
        $data = [
            'user_name' => $this->userName,
            'user_context' => $this->userContext->value,
        ];

        if ($this->account) {
            $data['account_id'] = $this->account->id;
            $data['account_type'] = get_class($this->account);
        }

        return $data;
    }
}
