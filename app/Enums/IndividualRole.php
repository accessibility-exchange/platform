<?php

namespace App\Enums;

enum IndividualRole: string
{
    case ConsultationParticipant = 'participant';
    case AccessibilityConsultant = 'consultant';
    case CommunityConnector = 'connector';

    public static function labels(): array
    {
        return [
            'participant' => __('Consultation Participant'),
            'consultant' => __('Accessibility Consultant'),
            'connector' => __('Community Connector'),
        ];
    }

    public function description(): string
    {
        return match ($this) {
            self::AccessibilityConsultant => __('Help regulated organizations design and implement their consultations'),
            self::CommunityConnector => __('Connect organizations with participants from my community'),
            self::ConsultationParticipant => __('Participate in consultations'),
        };
    }
}
