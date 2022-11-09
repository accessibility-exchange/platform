<?php

namespace App\Enums;

enum ResourceFormat: string
{
    case Text = 'text';
    case Video = 'video';
    case Audio = 'audio';
    case PDF = 'pdf';
    case Word = 'word';

    public static function labels(): array
    {
        return [
            'text' => __('Text'),
            'video' => __('Video'),
            'audio' => __('Audio'),
            'pdf' => __('PDF'),
            'word' => __('Word document'),
        ];
    }
}
