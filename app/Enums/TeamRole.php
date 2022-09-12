<?php

namespace App\Enums;

enum TeamRole: string
{
    case Member = 'member';
    case Manager = 'manager';
    case Administrator = 'admin';

    public static function labels(): array
    {
        return [
            'member' => __('Member'),
            'manager' => __('Manager'),
            'admin' => __('Administrator'),
        ];
    }
}
