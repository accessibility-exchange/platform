<?php

use App\Enums\RegulatedOrganizationType;
use App\Models\RegulatedOrganization;

dataset('storeRegulatedOrganizationRequestValidationErrors', function () {
    $businessType = RegulatedOrganizationType::Business->value;

    return [
        'Type is missing' => [
            'state' => ['type' => null],
            'errors' => fn () => ['type' => __('validation.required', ['attribute' => __('organization type')])],
        ],
        'Type is not a string' => [
            'state' => ['type' => false],
            'errors' => fn () => ['type' => __('validation.string', ['attribute' => __('organization type')])],
        ],
        'Type is invalid' => [
            'state' => ['type' => 'other'],
            'errors' => fn () => ['type' => __('validation.exists', ['attribute' => __('organization type')])],
        ],
        'Name is missing' => [
            'state' => ['name' => null],
            'errors' => fn () => [
                'name.en' => __('You must enter your organization name in either English or French.'),
                'name.fr' => __('You must enter your organization name in either English or French.'),
            ],
        ],
        'Name is not unique' => [
            'state' => fn () => [
                'type' => $businessType,
                'name' => RegulatedOrganization::factory()->create(['name' => ['en' => 'english name', 'fr' => 'nom français']])->getTranslations('name'),
            ],
            'errors' => fn () => [
                'name.en' => __('A :type with this name already exists.', ['type' => $businessType]),
                'name.fr' => __('A :type with this name already exists.', ['type' => $businessType]),
            ],
        ],
    ];
});
