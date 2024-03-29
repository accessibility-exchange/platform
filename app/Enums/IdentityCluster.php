<?php

namespace App\Enums;

enum IdentityCluster: string
{
    case Age = 'age';
    case Area = 'area';
    case DisabilityAndDeaf = 'disability-and-deaf';
    case Ethnoracial = 'ethnoracial';
    case LivedExperience = 'lived-experience';
    case Family = 'family';
    case Gender = 'gender';
    case GenderAndSexuality = 'gender-and-sexuality';
    case GenderDiverse = 'gender-diverse';
    case Indigenous = 'indigenous';
    case Status = 'status';
    // This cluster contains identities that cannot be directly recruited but will be
    // included in cross-disability and Deaf or intersectional groups.
    case OnlyReachableWithinMixedGroups = 'reachable-when-mixed';

    public static function labels(): array
    {
        return [
            'age' => __('Age group'),
            'area' => __('Area'),
            'disability-and-deaf' => __('Disability and/or Deaf identity'),
            'ethnoracial' => __('Ethnoracial identity'),
            'lived-experience' => __('Lived experience'),
            'family' => __('Family'),
            'gender' => __('Gender identity'),
            'gender-and-sexuality' => __('Gender and sexuality'),
            'gender-diverse' => __('Gender diverse'),
            'indigenous' => __('Indigenous identity'),
            'status' => __('Status'),
            'reachable-when-mixed' => __('Only reachable within cross-disability and Deaf or intersectional groups'),
        ];
    }
}
