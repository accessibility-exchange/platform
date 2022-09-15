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

    public function description(): string
    {
        return match ($this) {
            self::Member => __('Can only view the organization page, projects, and engagements.'),
            self::Administrator => __('Can create and edit the organization page, projects, and engagements.'),
        };
    }
}
