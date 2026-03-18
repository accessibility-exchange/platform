<?php

namespace App\Notifications;

use App\Enums\UserContext;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserRegistered extends PlatformNotification
{
    public function __construct(
        public string $userName,
        public string $userEmail,
        public string $userContext,
    ) {}

    private function contextLabel(): string
    {
        return UserContext::labels()[$this->userContext] ?? $this->userContext;
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New user registered'))
            ->markdown(
                'mail.new-user-registered',
                [
                    'userName' => $this->userName,
                    'userEmail' => $this->userEmail,
                    'contextLabel' => $this->contextLabel(),
                ]
            );
    }

    public function toArray(): array
    {
        return [
            'title' => __('New user registered'),
            'body' => __('A new user, :name, has registered as :context.', [
                'name' => $this->userName,
                'context' => $this->contextLabel(),
            ]),
        ];
    }
}
