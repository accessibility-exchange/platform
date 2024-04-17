<?php

namespace App\Enums;

enum ProjectInitiator: string
{
    case Organization = 'organization';
    case RegulatedOrganization = 'regulated-organization';

    public static function labels(): array
    {
        return [
            'organization' => __('Community organization'),
            'regulated-organization' => __('Regulated organization'),
        ];
    }
}
