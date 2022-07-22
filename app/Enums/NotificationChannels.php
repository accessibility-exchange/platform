<?php

namespace App\Enums;

enum NotificationChannels: string
{
    case Website = 'website';
    case Contact = 'contact';

    public static function labels(): array
    {
        return [
            'website' => __('Through the website'),
            'contact' => __('Through contacting me or my support person'),
        ];
    }
}
