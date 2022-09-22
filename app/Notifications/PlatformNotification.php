<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PlatformNotification extends Notification
{
    use Queueable;

    public function via(mixed $notifiable): array
    {
        return ['mail', 'database'];
        // TODO: Configure SMS notifications
        // return $notifiable->preferred_notification_method === 'sms' ? ['vonage', 'database'] : ['mail', 'database'];
    }
}
