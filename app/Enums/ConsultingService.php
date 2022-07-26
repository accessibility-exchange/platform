<?php

namespace App\Enums;

enum ConsultingService: string
{
    case BookingServiceProviders = 'booking-providers';
    case PlanningConsultation = 'planning-consultation';
    case RunningConsultation = 'running-consultation';
    case Analysis = 'analysis';
    case WritingReports = 'writing-reports';

    public static function labels(): array
    {
        return [
            'booking-providers' => __('consulting-services.booking-providers'),
            'planning-consultation' => __('consulting-services.planning-consultation'),
            'running-consultation' => __('consulting-services.running-consultation'),
            'analysis' => __('consulting-services.analysis'),
            'writing-reports' => __('consulting-services.writing-reports'),
        ];
    }
}
