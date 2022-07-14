<?php

namespace App\Enums;

enum CommunityConnectorHasLivedExperience: string
{
    case YesAll = 'yes-all';
    case YesSome = 'yes-some';
    case No = 'no';
    case PreferNotToAnswer = 'prefer-not-to-answer';

    public static function labels(): array
    {
        return [
            'yes-all' => __('Yes, all'),
            'yes-some' => __('Yes, some'),
            'no' => __('No'),
            'prefer-not-to-answer' => __('Prefer not to answer'),
        ];
    }
}
