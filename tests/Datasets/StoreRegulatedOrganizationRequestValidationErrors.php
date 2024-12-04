<?php

use App\Enums\RegulatedOrganizationType;
use App\Models\RegulatedOrganization;

dataset('storeRegulatedOrganizationRequestValidationErrors', function () {
    $businessType = RegulatedOrganizationType::Business->value;

    return [
        'Type is missing' => fn () => [
            'state' => ['type' => null],
            'errors' => ['type' => __('validation.required', ['attribute' => __('organization type')])],
        ],
        'Type is not a string' => fn () => [
            'state' => ['type' => false],
            'errors' => ['type' => __('validation.string', ['attribute' => __('organization type')])],
        ],
        'Type is invalid' => fn () => [
            'state' => ['type' => 'other'],
            'errors' => ['type' => __('validation.exists', ['attribute' => __('organization type')])],
        ],
        'Name is missing' => fn () => [
            'state' => ['name' => null],
            'errors' => [
                'name.en' => __('You must enter your organization name in either English or French.'),
                'name.fr' => __('You must enter your organization name in either English or French.'),
            ],
        ],
        'Name is not unique' => fn () => [
            'state' => [
                'type' => $businessType,
                'name' => RegulatedOrganization::factory()->create(['name' => ['en' => 'english name', 'fr' => 'nom français']])->getTranslations('name'),
            ],
            'errors' => [
                'name.en' => __('A :type with this name already exists.', ['type' => $businessType]),
                'name.fr' => __('A :type with this name already exists.', ['type' => $businessType]),
            ],
        ],
    ];
});
