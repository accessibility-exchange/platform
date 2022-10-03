<?php

namespace App\Enums;

enum TimeZone: string
{
    case Pacific = 'America/Vancouver';
    case MountainStandard = 'America/Whitehorse';
    case Mountain = 'America/Edmonton';
    case CentralStandard = 'America/Regina';
    case Central = 'America/Winnipeg';
    case Atlantic = 'America/Halifax';
    case Newfoundland = 'America/St_Johns';

    public static function labels(): array
    {
        return [
            'America/Vancouver' => __('Pacific Standard or Daylight Time'),
            'America/Whitehorse' => __('Mountain Standard Time*'),
            'America/Edmonton' => __('Mountain Standard or Daylight Time'),
            'America/Regina' => __('Central Standard Time**'),
            'America/Winnipeg' => __('Central Standard or Daylight Time'),
            'America/Halifax' => __('Atlantic Standard or Daylight Time'),
            'America/St_Johns' => __('Newfoundland Standard or Daylight Time'),
        ];
    }
}
