<?php

use App\Models\Organization;

dataset('storeOrganizationRequestValidationErrors', function () {
    return [
        'Type is missing' => [
            'state' => ['type' => null],
            'errors' => fn () => ['type' => __('validation.required', ['attribute' => __('organization type')])],
        ],
        'Type is invalid' => [
            'state' => ['type' => 'other'],
            'errors' => fn () => ['type' => __('validation.exists', ['attribute' => __('organization type')])],
        ],
        'Name is missing' => [
            'state' => ['name' => null],
            'errors' => fn () => [
                'name.en' => __('You must enter your organization’s name in either English or French.'),
                'name.fr' => __('You must enter your organization’s name in either English or French.'),
            ],
        ],
        'Name is not unique' => [
            'state' => fn () => ['name' => Organization::factory()->create(['name' => ['en' => 'english name', 'fr' => 'nom français']])->getTranslations('name')],
            'errors' => fn () => [
                'name.en' => __('An organization with this name already exists on our website. Please contact your colleagues to get an invitation. If this isn’t your organization, please use a different name.'),
                'name.fr' => __('An organization with this name already exists on our website. Please contact your colleagues to get an invitation. If this isn’t your organization, please use a different name.'),
            ],
        ],
    ];
});
