<?php

use App\Enums\OrganizationRole;
use App\Models\Organization;

dataset('addOrganizationValidationErrors', function () {
    return [
        'Organization id is missing' => fn () => [
            'state' => ['organization_id' => null],
            'errors' => ['organization_id' => __('validation.required', ['attribute' => __('organization.singular_name')])],
        ],
        'Organization id is invalid' => fn () => [
            'state' => ['organization_id' => 1000000],
            'errors' => ['organization_id' => __('validation.exists', ['attribute' => __('organization.singular_name')])],
        ],
        'Organization is not a participant' => fn () => [
            'state' => ['organization_id' => Organization::factory()->create([
                'name' => 'not a participant org',
                'roles' => [OrganizationRole::CommunityConnector->value],
            ])->id],
            'errors' => ['organization_id' => __('The organization you have added does not participate in engagements.')],
        ],
    ];
});
