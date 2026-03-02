<?php

namespace App\Notifications;

use App\Enums\UserContext;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrganizationRegistered extends PlatformNotification
{
    public string $contextLabel;

    public function __construct(
        public string $creatorName,
        public string $organizationName,
        public string $creatorEmail,
        public string $userContext,
    ) {
        $this->contextLabel = UserContext::labels()[$this->userContext] ?? $this->userContext;
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New organization registered'))
            ->markdown(
                'mail.new-organization-registered',
                [
                    'creatorName' => $this->creatorName,
                    'organizationName' => $this->organizationName,
                    'creatorEmail' => $this->creatorEmail,
                    'contextLabel' => $this->contextLabel,
                ]
            );
    }

    public function toArray(): array
    {
        return [
            'title' => __('New organization registered'),
            'body' => __('A new organization, :organization, has been registered by :name as :context.', [
                'organization' => $this->organizationName,
                'name' => $this->creatorName,
                'context' => $this->contextLabel,
            ]),
        ];
    }
}
