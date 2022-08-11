<?php

namespace App\Enums;

enum OrganizationRole: string
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
            self::AccessibilityConsultant => __('Federally Regulated Entities can hire my organization to design and run consultations, as well as to synthesize results and to contribute systemic analysis'),
            self::CommunityConnector => __('Federally Regulated Entities can hire my organization to recruit Consultation Participants for them'),
            self::ConsultationParticipant => __('Allow Federally Regulated Entities to reach out to my organization to participate in consultation'),
        };
    }
}
