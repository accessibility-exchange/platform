<?php

use App\Models\Organization;

dataset('addOrganizationValidationErrors', function () {
    return [
        'Organization id is missing' => [
            'state' => ['organization_id' => null],
            'errors' => fn () => ['organization_id' => __('validation.required', ['attribute' => __('organization.singular_name')])],
        ],
        'Organization id is invalid' => [
            'state' => ['organization_id' => 1000000],
            'errors' => fn () => ['organization_id' => __('validation.exists', ['attribute' => __('organization.singular_name')])],
        ],
        'Organization is not a participant' => [
            'state' => fn () => ['organization_id' => Organization::factory()->create([
                'name' => 'not a participant org',
                'roles' => ['connector'],
            ])->id],
            'errors' => fn () => ['organization_id' => __('The organization you have added does not participate in engagements.')],
        ],
    ];
});
