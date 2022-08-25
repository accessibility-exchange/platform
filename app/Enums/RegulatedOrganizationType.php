<?php

namespace App\Enums;

enum RegulatedOrganizationType: string
{
    case Government = 'government';
    case Business = 'business';
    case OtherPublicSectorOrganization = 'public-sector';

    public static function labels(): array
    {
        return [
            'government' => __('Government'),
            'business' => __('Business'),
            'public-sector' => __('Other public sector organization, which is regulated by the Accessible Canada Act'),
        ];
    }
}
