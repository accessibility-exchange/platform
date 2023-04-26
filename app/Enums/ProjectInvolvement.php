<?php

namespace App\Enums;

enum ProjectInvolvement: string
{
    case Contracted = 'contracted';
    case Participating = 'participating';
    case Running = 'running';

    public static function labels(): array
    {
        return [
            'contracted' => __('Contracted'),
            'participating' => __('Participating'),
            'running' => __('Running'),
        ];
    }
}
