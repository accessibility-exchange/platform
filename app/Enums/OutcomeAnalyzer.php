<?php

namespace App\Enums;

enum OutcomeAnalyzer: string
{
    case Internal = 'internal';
    case External = 'external';

    public static function labels(): array
    {
        return [
            'internal' => __('Internal team'),
            'external' => __('External team'),
        ];
    }
}
