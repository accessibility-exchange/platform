<?php

namespace App\Notifications;

use App\Enums\UserContext;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserRegistered extends PlatformNotification
{
    public function __construct(
        public string $userName,
        public UserContext $userContext,
    ) {}

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New user registered'))
            ->line(__('A new user has registered on The Accessibility Exchange.'))
            ->action(__('Dashboard'), localized_route('dashboard'));
    }

    public function toArray(): array
    {
        return [
            'user_name' => $this->userName,
            'user_context' => $this->userContext->value,
        ];
    }
}
