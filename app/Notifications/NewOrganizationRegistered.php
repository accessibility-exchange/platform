<?php

namespace App\Notifications;

use App\Enums\UserContext;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrganizationRegistered extends PlatformNotification
{
    public function __construct(
        public string $creatorName,
        public Organization|RegulatedOrganization $organization,
        public string $creatorEmail,
        public string $userContext,
    ) {}

    private function contextLabel(): string
    {
        return UserContext::labels()[$this->userContext] ?? $this->userContext;
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New organization registered'))
            ->markdown(
                'mail.new-organization-registered',
                [
                    'creatorName' => $this->creatorName,
                    'organizationName' => $this->organization->getTranslation('name', locale()),
                    'creatorEmail' => $this->creatorEmail,
                    'contextLabel' => $this->contextLabel(),
                ]
            );
    }

    public function toArray(): array
    {
        return [
            'title' => __('New organization registered'),
            'body' => __('A new organization, :organization, has been registered by :name as :context.', [
                'organization' => $this->organization->getTranslation('name', locale()),
                'name' => $this->creatorName,
                'context' => $this->contextLabel(),
            ]),
        ];
    }
}
