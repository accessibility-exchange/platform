<?php

namespace App\Enums;

enum TeamRole: string
{
    case Member = 'member';
    case Administrator = 'admin';

    public static function labels(): array
    {
        return [
            'member' => __('Member'),
            'admin' => __('Administrator'),
        ];
    }
}
