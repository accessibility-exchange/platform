<?php

namespace App\Enums;

enum ProvinceOrTerritory: string
{
    case Alberta = 'AB';
    case BritishColumbia = 'BC';
    case Manitoba = 'MB';
    case NewBrunswick = 'NB';
    case NewfoundlandAndLabrador = 'NL';
    case NorthwestTerritories = 'NT';
    case NovaScotia = 'NS';
    case Nunavut = 'NU';
    case Ontario = 'ON';
    case PrinceEdwardIsland = 'PE';
    case Quebec = 'QC';
    case Saskatchewan = 'SK';
    case YukonTerritory = 'YT';

    public static function labels(): array
    {
        return [
            'AB' => __('Alberta'),
            'BC' => __('British Columbia'),
            'MB' => __('Manitoba'),
            'NB' => __('New Brunswick'),
            'NL' => __('Newfoundland and Labrador'),
            'NT' => __('Northwest Territories'),
            'NS' => __('Nova Scotia'),
            'NU' => __('Nunavut'),
            'ON' => __('Ontario'),
            'PE' => __('Prince Edward Island'),
            'QC' => __('Quebec'),
            'SK' => __('Saskatchewan'),
            'YT' => __('Yukon Territory'),
        ];
    }
}
