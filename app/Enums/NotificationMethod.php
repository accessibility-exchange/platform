<?php

namespace App\Enums;

enum NotificationMethod: string
{
    case Email = 'email';
    case Phone = 'phone';
    case Text = 'sms';

    public static function labels(): array
    {
        return [
            'email' => __('Email'),
            'phone' => __('Phone call'),
            'sms' => __('Text message'),
        ];
    }
}
