<?php

namespace App\Enums;

enum SeekingForEngagement: string
{
    case Participants = 'participants';
    case Connectors = 'connectors';
    case Organizations = 'organizations';

    public static function labels(): array
    {
        return [
            'participants' => __('Seeking Individual Consultation Participants'),
            'connectors' => __('Seeking Community Connectors'),
            'organizations' => __('Seeking Community Organizations to consult with'),
        ];
    }
}
