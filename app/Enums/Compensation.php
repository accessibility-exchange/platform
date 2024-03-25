<?php

namespace App\Enums;

enum Compensation: string
{
    case Paid = 'paid';
    case Volunteer = 'volunteer';

    public static function labels(): array
    {
        return [
            'paid' => __('Paid'),
            'volunteer' => __('Volunteer'),
        ];
    }
}
