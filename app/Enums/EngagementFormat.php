<?php

namespace App\Enums;

enum EngagementFormat: string
{
    case Survey = 'survey';
    case Interview = 'interview';
    case FocusGroup = 'focus-group';
    case Workshop = 'workshop';
    case OtherSync = 'other-sync';
    case OtherAsync = 'other-async';

    public static function labels(): array
    {
        return [
            'survey' => __('Survey'),
            'interview' => __('Interview'),
            'focus-group' => __('Focus group'),
            'workshop' => __('Workshop'),
            'other-sync' => __('Other – in-person or virtual meeting'),
            'other-async' => __('Other – written or recorded response'),
        ];
    }
}
