<?php

namespace App\Enums;

enum AcceptedFormat: string
{
    case Writing = 'writing';
    case Audio = 'audio';
    case Video = 'video';

    public static function labels(): array
    {
        return [
            'writing' => __('Writing'),
            'audio' => __('Voice recording'),
            'video' => __('Video recording'),
        ];
    }
}
