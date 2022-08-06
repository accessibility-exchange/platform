<?php

namespace App\Enums;

enum MeetingType: string
{
    case InPerson = 'in_person';
    case WebConference = 'web_conference';
    case Phone = 'phone';

    public static function labels(): array
    {
        return [
            'in_person' => __('In person'),
            'web_conference' => __('Virtual – web conference'),
            'phone' => __('Virtual – phone call'),
        ];
    }
}
