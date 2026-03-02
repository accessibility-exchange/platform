<?php

namespace App\Notifications;

use App\Enums\UserContext;
use Illuminate\Notifications\Messages\MailMessage;

class NewMemberJoinedOrganization extends PlatformNotification
{
    public string $contextLabel;

    public function __construct(
        public string $memberName,
        public string $organizationName,
        public string $memberEmail,
        public string $memberRole,
        public string $userContext,
    ) {
        $this->contextLabel = UserContext::labels()[$this->userContext] ?? $this->userContext;
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New member joined organization'))
            ->markdown(
                'mail.new-member-joined-organization',
                [
                    'memberName' => $this->memberName,
                    'organizationName' => $this->organizationName,
                    'memberEmail' => $this->memberEmail,
                    'memberRole' => $this->memberRole,
                    'contextLabel' => $this->contextLabel,
                ]
            );
    }

    public function toArray(): array
    {
        return [
            'title' => __('New member joined organization'),
            'body' => __(':name has joined :organization as :role.', [
                'name' => $this->memberName,
                'organization' => $this->organizationName,
                'role' => $this->memberRole,
            ]),
        ];
    }
}
