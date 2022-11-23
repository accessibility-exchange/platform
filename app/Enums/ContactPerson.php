<?php

namespace App\Enums;

enum ContactPerson: string
{
    case Me = 'me';
    case SupportPerson = 'support-person';

    public static function labels(): array
    {
        return [
            'me' => __('Me'),
            'support-person' => __('My support person'),
        ];
    }
}
