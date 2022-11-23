<?php

namespace App\Enums;

enum BaseDisabilityType: string
{
    case CrossDisability = 'cross_disability';
    case SpecificDisabilities = 'specific_disabilities';

    public static function labels(): array
    {
        return [
            'cross_disability' => __('I can connect to people across any disabilities and Deaf people'),
            'specific_disabilities' => __('I can only connect to people with specific disabilities and/or Deaf people'),
        ];
    }
}
