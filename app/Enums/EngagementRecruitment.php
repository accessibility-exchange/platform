<?php

namespace App\Enums;

enum EngagementRecruitment: string
{
    case OpenCall = 'open-call';
    case CommunityConnector = 'connector';

    public static function labels(): array
    {
        return [
            'open-call' => __('Open call'),
            'connector' => __('Community Connector'),
        ];
    }
}
