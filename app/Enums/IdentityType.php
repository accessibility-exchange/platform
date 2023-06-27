<?php

namespace App\Enums;

enum IdentityType: string
{
    case AgeBracket = 'age-bracket';
    case GenderAndSexualIdentity = 'gender-and-sexual-identity';
    case IndigenousIdentity = 'indigenous-identity';
    case EthnoracialIdentity = 'ethnoracial-identity';
    case RefugeeOrImmigrant = 'refugee-or-immigrant';
    case FirstLanguage = 'first-language';
    case AreaType = 'area-type';

    public static function labels(): array
    {
        return [
            'age-bracket' => __('Age'),
            'gender-and-sexual-identity' => __('Gender and sexual identity'),
            'indigenous-identity' => __('Indigenous'),
            'ethnoracial-identity' => __('Race and ethnicity'),
            'refugee-or-immigrant' => __('Refugees and/or immigrants'),
            'first-language' => __('First language'),
            'area-type' => __('Living in urban, rural, or remote areas'),
        ];
    }
}
