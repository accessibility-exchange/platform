<?php

use App\Models\Organization;

dataset('storeOrganizationRequestValidationErrors', function () {
    return [
        'Type is missing' => fn () => [
            'state' => ['type' => null],
            'errors' => ['type' => __('validation.required', ['attribute' => __('organization type')])],
        ],
        'Type is invalid' => fn () => [
            'state' => ['type' => 'other'],
            'errors' => ['type' => __('validation.exists', ['attribute' => __('organization type')])],
        ],
        'Name is missing' => fn () => [
            'state' => ['name' => null],
            'errors' => [
                'name.en' => __('You must enter your organization’s name in either English or French.'),
                'name.fr' => __('You must enter your organization’s name in either English or French.'),
            ],
        ],
        'Name is not unique' => fn () => [
            'state' => ['name' => Organization::factory()->create(['name' => ['en' => 'english name', 'fr' => 'nom français']])->getTranslations('name')],
            'errors' => [
                'name.en' => __('An organization with this name already exists on our website. Please contact your colleagues to get an invitation. If this isn’t your organization, please use a different name.'),
                'name.fr' => __('An organization with this name already exists on our website. Please contact your colleagues to get an invitation. If this isn’t your organization, please use a different name.'),
            ],
        ],
    ];
});
