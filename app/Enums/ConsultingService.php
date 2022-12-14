<?php

namespace App\Enums;

enum ConsultingService: string
{
    case BookingServiceProviders = 'booking-providers';
    case DesigningConsultation = 'designing-consultation';
    case RunningConsultation = 'running-consultation';
    case Analysis = 'analysis';
    case WritingReports = 'writing-reports';
    case DevelopingPlan = 'developing-plan';

    public static function labels(): array
    {
        return [
            'booking-providers' => __('Booking accessibility service providers'),
            'designing-consultation' => __('Designing a consultation'),
            'running-consultation' => __('Running a consultation'),
            'analysis' => __('Analysis of collected information'),
            'writing-reports' => __('Writing accessibility reports'),
            'developing-plan' => __('Developing an accessibility plan'),
        ];
    }
}
