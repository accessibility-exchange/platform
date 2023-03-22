<?php

use App\Enums\IndividualRole;

dataset('individualRoleChange', function () {
    $allRoles = array_column(IndividualRole::cases(), 'value');
    $consultantRole = IndividualRole::AccessibilityConsultant->value;
    $connectorRole = IndividualRole::CommunityConnector->value;
    $participantRole = IndividualRole::ConsultationParticipant->value;
    $success = [
        'class' => 'success',
        'message' => fn () => __('Your roles have been saved.'),
    ];
    $warning = [
        'class' => 'warning',
        'message' => fn ($individual) => __('Your roles have been saved.').' '.__('Please review your page. There is some information for your new role that you will have to fill in.').' <a href="'.localized_route('individuals.edit', $individual).'">'.__('Review page').'</a>', 'warning',
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
