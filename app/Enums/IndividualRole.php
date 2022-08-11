<?php

namespace App\Enums;

enum IndividualRole: string
{
    case AccessibilityConsultant = 'consultant';
    case CommunityConnector = 'connector';
    case ConsultationParticipant = 'participant';

    public static function labels(): array
    {
        return [
            'consultant' => __('Accessibility Consultant'),
            'connector' => __('Community Connector'),
            'participant' => __('Consultation Participant'),
        ];
    }

    public static function descriptions(): array
    {
        return [
            'consultant' => __('Help regulated organizations design and implement their consultations'),
            'connector' => __('Connect organizations with participants from my community'),
            'participant' => __('Participate in consultations'),
        ];
    }
}
