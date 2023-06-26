<?php

namespace App\Enums;

enum LocationType: string
{
    case Regions = 'regions';
    case Localities = 'localities';

    public static function labels(): array
    {
        return [
            'regions' => __('Specific provinces or territories'),
            'localities' => __('Specific cities or towns'),
        ];
    }
}
