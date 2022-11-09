<?php

namespace App\Enums;

enum IdentityCluster: string
{
    case Age = 'age';
    case Area = 'area';
    case DisabilityAndDeaf = 'disability-and-deaf';
    case Ethnoracial = 'ethnoracial';
    case Experience = 'experience';
    case Gender = 'gender';
    case Indigenous = 'indigenous';

    public static function labels(): array
    {
        return [
            'age' => __('Age group'),
            'area' => __('Area'),
            'disability-and-deaf' => __('Disability and/or Deaf identity'),
            'ethnoracial' => __('Ethnoracial identity'),
            'experience' => __('Lived experience'),
            'gender' => __('Gender identity'),
            'indigenous' => __('Indigenous identity'),
        ];
    }
}
