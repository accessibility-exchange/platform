<?php

namespace App\Enums;

enum ContactMethod: string
{
    case Email = 'email';
    case Phone = 'phone';

    public static function labels(): array
    {
        return [
            'email' => __('Email'),
            'phone' => __('Phone'),
        ];
    }
}
