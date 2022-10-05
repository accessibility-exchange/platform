<?php

namespace App\Enums;

enum Availability: string
{
    case Available = 'yes';
    case UponRequest = 'upon-request';
    case NotAvailable = 'no';

    public static function labels(): array
    {
        return [
            'yes' => __('Available'),
            'upon-request' => __('Upon request'),
            'no' => __('Not available'),
        ];
    }
}
