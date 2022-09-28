<?php

namespace App\Enums;

enum EngagementFormat: string
{
    case Survey = 'survey';
    case Interviews = 'interviews';
    case FocusGroup = 'focus-group';
    case Workshop = 'workshop';
    case OtherSync = 'other-sync';
    case OtherAsync = 'other-async';

    public static function labels(): array
    {
        return [
            'survey' => __('Survey'),
            'interviews' => __('Interviews'),
            'focus-group' => __('Focus group'),
            'workshop' => __('Workshop'),
            'other-sync' => __('Other – in-person or virtual meeting'),
            'other-async' => __('Other – written or recorded response'),
        ];
    }
}
