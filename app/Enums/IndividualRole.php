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

    public function description(): string
    {
        return match ($this) {
            self::AccessibilityConsultant => __('Help regulated organizations design and implement their consultations'),
            self::CommunityConnector => __('Connect organizations with participants from my community'),
            self::ConsultationParticipant => __('Participate in consultations'),
        };
    }
}
