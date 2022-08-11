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

    public static function descriptions(): array
    {
        return [
            'consultant' => __('Federally Regulated Entities can hire my organization to design and run consultations, as well as to synthesize results and to contribute systemic analysis'),
            'connector' => __('Federally Regulated Entities can hire my organization to recruit Consultation Participants for them'),
            'participant' => __('Allow Federally Regulated Entities to reach out to my organization to participate in consultation'),
        ];
    }
}
