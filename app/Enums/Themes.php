<?php

namespace App\Enums;

enum Themes: string
{
    case Light = 'light';
    case Dark = 'dark';
    case System = 'system';
    case BlackOnWhite = 'black-white';
    case WhiteOnBlack = 'white-black';
    case BlackOnYellow = 'black-yellow';
    case YellowOnBlack = 'yellow-black';
    case LightGreyOnDarkGrey = 'light-dark';
    case GreyOnWhite = 'grey-white';
    case GreyOnDarkGrey = 'grey-dark';
    case BlackOnBrown = 'black-brown';

    public static function labels(): array
    {
        return [
            'light' => __('Light theme'),
            'dark' => __('Dark theme'),
            'system' => __('System theme'),
            'black-white' => __('Black on white'),
            'white-black' => __('White on black'),
            'black-yellow' => __('Black on yellow'),
            'yellow-black' => __('Yellow on black'),
            'light-dark' => __('Light grey on grey'),
            'grey-white' => __('Grey on white'),
            'grey-dark' => __('Grey on dark grey'),
            'black-brown' => __('Black on brown'),
        ];
    }
}
