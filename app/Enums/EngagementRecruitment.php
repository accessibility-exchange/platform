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

    public function description(): string
    {
        return match ($this) {
            self::OpenCall => __('Post your engagement as an open call. Anyone who fits your selection criteria can sign up. It is first-come, first-served until the number of participants you are seeking has been reached.'),
            self::CommunityConnector => __('Hire a Community Connector (who can be an individual or a Community Organization) to recruit people manually from within their networks. This option is best if you are looking for a specific or hard-to-reach group.'),
        };
    }
}
