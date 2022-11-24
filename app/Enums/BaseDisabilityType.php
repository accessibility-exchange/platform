<?php

namespace App\Enums;

enum BaseDisabilityType: string
{
    case CrossDisability = 'cross_disability';
    case SpecificDisabilities = 'specific_disabilities';

    public static function labels(): array
    {
        return [
            'cross_disability' => __('People across any disabilities and Deaf people'),
            'specific_disabilities' => __('Only people with specific disabilities and/or Deaf people'),
        ];
    }
}
