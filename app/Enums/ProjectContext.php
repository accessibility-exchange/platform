<?php

namespace App\Enums;

enum ProjectContext: string
{
    case New = 'new';
    case FollowUp = 'follow-up';

    public static function labels(): array
    {
        return [
            'new' => __('A new project'),
            'follow-up' => __('A follow-up to a previous project (such as a progress report)'),
        ];
    }
}
