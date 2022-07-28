<?php

namespace App\Enums;

enum NotificationChannel: string
{
    case Website = 'website';
    case Contact = 'contact';

    public static function labels(): array
    {
        return [
            'website' => __('Notify me through the website'),
            'contact' => __('Notify me or my support person directly'),
        ];
    }
}
