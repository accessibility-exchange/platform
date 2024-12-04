<?php

use App\Enums\OrganizationRole;

dataset('organizationRoleChange', function () {
    $allRoles = array_column(OrganizationRole::cases(), 'value');
    $consultantRole = OrganizationRole::AccessibilityConsultant->value;
    $connectorRole = OrganizationRole::CommunityConnector->value;
    $participantRole = OrganizationRole::ConsultationParticipant->value;
    $success = [
        'class' => 'success',
        'message' => fn () => __('Your roles have been saved.'),
    ];
    $warning = [
        'class' => 'warning',
        'message' => fn () => __('Your roles have been saved.').' '.__('Please review your page. There is some information for your new role that you will have to fill in.'), 'warning',
        'notification' => true,
    ];

    return [
        'no previous roles' => [
            [],
            $allRoles,
            $success,
        ],
        'All roles to only AccessibilityConsultant role' => [
            $allRoles,
            [$consultantRole],
            $success,
        ],
        'All roles to only CommunityConnector role' => [
            $allRoles,
            [$connectorRole],
            $success,
        ],
        'All roles to only ConsultationParticipant role' => [
            $allRoles,
            [$participantRole],
            $success,
        ],
        'From one to AccessibilityConsultant role' => [
            [$participantRole],
            [$consultantRole],
            $warning,
        ],
        'From one to CommunityConnector role' => [
            [$participantRole],
            [$connectorRole],
            $warning,
        ],
        'From one to ConsultationParticipant role' => [
            [$consultantRole],
            [$participantRole],
            $success,
        ],
    ];
});
