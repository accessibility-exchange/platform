<?php

namespace App\Notifications;

use App\Enums\UserContext;
use Illuminate\Notifications\Messages\MailMessage;

class UserAccountDeleted extends PlatformNotification
{
    public string $contextLabel;

    public function __construct(
        public string $userName,
        public string $userEmail,
        public string $userContext,
        public ?string $organizationName = null,
    ) {
        $this->contextLabel = UserContext::labels()[$this->userContext] ?? $this->userContext;
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('User account deleted'))
            ->markdown(
                'mail.user-account-deleted',
                [
                    'userName' => $this->userName,
                    'userEmail' => $this->userEmail,
                    'contextLabel' => $this->contextLabel,
                    'organizationName' => $this->organizationName,
                ]
            );
    }

    public function toArray(): array
    {
        $body = $this->organizationName
            ? __(':name (:context) from :organization has deleted their account.', [
                'name' => $this->userName,
                'context' => $this->contextLabel,
                'organization' => $this->organizationName,
            ])
            : __(':name (:context) has deleted their account.', [
                'name' => $this->userName,
                'context' => $this->contextLabel,
            ]);

        return [
            'title' => __('User account deleted'),
            'body' => $body,
        ];
    }
}
