<?php

namespace App\Enums;

enum EngagementSignUpStatus: string
{
    case Open = 'open';
    case Closed = 'closed';

    public static function labels(): array
    {
        return [
            'open' => __('Open'),
            'closed' => __('Closed'),
        ];
    }
}
