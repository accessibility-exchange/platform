<?php

namespace App\Enums;

enum OrganizationNotificationChannel: string
{
    case Website = 'website';
    case Contact = 'contact';

    public static function labels(): array
    {
        return [
            'website' => __('Notify your organization’s team through the website'),
            'contact' => __('Notify your organization’s contact person directly'),
        ];
    }
}
