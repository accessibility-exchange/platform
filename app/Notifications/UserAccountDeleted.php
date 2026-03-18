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
        public string $userEmail,
        public string $userContext,
        public Organization|RegulatedOrganization|null $organization = null,
    ) {}

    private function contextLabel(): string
    {
        return UserContext::labels()[$this->userContext] ?? $this->userContext;
    }

    private function organizationName(): ?string
    {
        return $this->organization?->getTranslation('name', locale());
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
                    'contextLabel' => $this->contextLabel(),
                    'organizationName' => $this->organizationName(),
                ]
            );
    }

    public function toArray(): array
    {
        $body = $this->organization
            ? __(':name (:context) from :organization has deleted their account.', [
                'name' => $this->userName,
                'context' => $this->contextLabel(),
                'organization' => $this->organizationName(),
            ])
            : __(':name (:context) has deleted their account.', [
                'name' => $this->userName,
                'context' => $this->contextLabel(),
            ]);

        return [
            'title' => __('User account deleted'),
            'body' => $body,
        ];
    }
}
