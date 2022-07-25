<?php

namespace App\Enums;

enum NotificationMethods: string
{
    case Email = 'email';
    case Phone = 'phone';
    case Text = 'sms';

    public static function labels(): array
    {
        return [
            'email' => __('Email'),
            'phone' => __('Phone'),
            'sms' => __('Text message'),
        ];
    }
}
