<?php

namespace App\Notifications;

use App\Enums\UserContext;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use Illuminate\Notifications\Messages\MailMessage;

class NewMemberJoinedOrganization extends PlatformNotification
{
    public function __construct(
        public string $memberName,
        public Organization|RegulatedOrganization $organization,
        public string $memberEmail,
        public string $memberRole,
        public string $userContext,
    ) {}

    private function contextLabel(): string
    {
        return UserContext::labels()[$this->userContext] ?? $this->userContext;
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New member joined organization'))
            ->markdown(
                'mail.new-member-joined-organization',
                [
                    'memberName' => $this->memberName,
                    'organizationName' => $this->organization->getTranslation('name', locale()),
                    'memberEmail' => $this->memberEmail,
                    'memberRole' => $this->memberRole,
                    'contextLabel' => $this->contextLabel(),
                ]
            );
    }

    public function toArray(): array
    {
        return [
            'title' => __('New member joined organization'),
            'body' => __(':name has joined :organization as :role.', [
                'name' => $this->memberName,
                'organizationName' => $this->organization->getTranslation('name', locale()),
                'role' => $this->memberRole,
            ]),
        ];
    }
}
