<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserRegistered extends PlatformNotification
{
    public function __construct(
        public User $user,
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
            'user_id' => $this->user->id,
        ];
    }
}
