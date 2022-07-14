<?php

namespace App\Enums;

enum BaseDisabilityType: string
{
    case CrossDisability = 'cross_disability';
    case SpecificDisabilities = 'specific_disabilities';

    public static function labels(): array
    {
        return [
            'cross_disability' => __('Cross-disability'),
            'specific_disabilities' => __('Specific disability or disabilities'),
        ];
    }
}
