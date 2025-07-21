<?php

namespace App\Enums;

enum YesNo: string
{
    case Yes = '1';
    case No = '0';

    public static function labels(): array
    {
        return [
            '1' => __('Yes'),
            '0' => __('No'),
        ];
    }
}
