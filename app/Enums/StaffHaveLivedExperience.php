<?php

namespace App\Enums;

enum StaffHaveLivedExperience: string
{
    case Yes = 'yes';
    case No = 'no';
    case PreferNotToAnswer = 'prefer-not-to-answer';

    public static function labels(): array
    {
        return [
            'yes' => __('Yes'),
            'no' => __('No'),
            'prefer-not-to-answer' => __('Prefer not to answer'),
        ];
    }
}
