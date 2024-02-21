<?php

use App\Models\Organization;

dataset('updateOrganizationRequestValidationErrors', function () {
    return [
        'Name is missing' => [
            'state' => ['name' => null],
            'errors' => fn () => [
                'name.en' => __('You must enter your organization name.'),
                'name.fr' => __('You must enter your organization name.'),
            ],
        ],
        'Name is not unique' => [
            'state' => fn () => ['name' => Organization::factory()->create(['name' => ['en' => 'english name', 'fr' => 'nom français']])->getTranslations('name')],
            'errors' => fn () => [
                'name.en' => __('validation.unique', ['attribute' => __('organization name (English)')]),
                'name.fr' => __('validation.unique', ['attribute' => __('organization name (French)')]),
            ],
        ],
        'Name is not a string' => [
            'state' => ['name' => ['en' => 123, 'fr' => false]],
            'errors' => fn () => [
                'name.en' => __('validation.string', ['attribute' => __('organization name (English)')]),
                'name.fr' => __('validation.string', ['attribute' => __('organization name (French)')]),
            ],
        ],
        'About is missing' => [
            'state' => ['about' => null],
            'errors' => fn () => [
                'about.en' => __('“About your organization” must be provided in either English or French.'),
                'about.fr' => __('“About your organization” must be provided in either English or French.'),
            ],
        ],
        'About is missing required translation' => [
            'state' => ['about' => ['es' => 'acerca de']],
            'errors' => fn () => [
                'about.en' => __('“About your organization” must be provided in either English or French.'),
                'about.fr' => __('“About your organization” must be provided in either English or French.'),
            ],
            'without' => ['about'],
        ],
        'About is not a string' => [
            'state' => ['about' => ['en' => [], 'fr' => false]],
            'errors' => fn () => [
                'about.en' => __('validation.string', ['attribute' => __('“About your organization” (English)')]),
                'about.fr' => __('validation.string', ['attribute' => __('“About your organization” (French)')]),
            ],
        ],
        'Region is missing' => [
            'state' => ['region' => null],
            'errors' => fn () => ['region' => __('validation.required', ['attribute' => __('province or territory')])],
        ],
        'Region is invalid' => [
            'state' => ['region' => 'yyz'],
            'errors' => fn () => ['region' => __('validation.exists', ['attribute' => __('province or territory')])],
        ],
        'Locality is missing' => [
            'state' => ['locality' => null],
            'errors' => fn () => ['locality' => __('validation.required', ['attribute' => __('city or town')])],
        ],
        'Locality is not a string' => [
            'state' => ['locality' => 123],
            'errors' => fn () => ['locality' => __('validation.string', ['attribute' => __('city or town')])],
        ],
        'Service areas is missing' => [
            'state' => ['service_areas' => null],
            'errors' => fn () => ['service_areas' => __('validation.required', ['attribute' => __('Service areas')])],
        ],
        'Service areas is not an array' => [
            'state' => ['service_areas' => 123],
            'errors' => fn () => ['service_areas' => __('validation.array', ['attribute' => __('Service areas')])],
        ],
        'Service area is invalid' => [
            'state' => ['service_areas' => ['yyz']],
            'errors' => fn () => ['service_areas.0' => __('validation.exists', ['attribute' => __('Service areas')])],
        ],
        'Working languages is missing' => [
            'state' => ['working_languages' => null],
            'errors' => fn () => ['working_languages' => __('validation.required', ['attribute' => __('Working languages')])],
        ],
        'Working languages is not an array' => [
            'state' => ['working_languages' => 123],
            'errors' => fn () => ['working_languages' => __('validation.array', ['attribute' => __('Working languages')])],
        ],
        'Consulting services is missing (org is accessibility consultant)' => [
            'state' => ['consulting_services' => null],
            'errors' => fn () => ['consulting_services' => __('validation.required', ['attribute' => __('Consulting services')])],
        ],
        'Consulting services is not an array' => [
            'state' => ['consulting_services' => 123],
            'errors' => fn () => ['consulting_services' => __('validation.array', ['attribute' => __('Consulting services')])],
        ],
        'Consulting service is invalid' => [
            'state' => ['consulting_services' => ['other']],
            'errors' => fn () => ['consulting_services.0' => __('validation.exists', ['attribute' => __('Consulting services')])],
        ],
        'Social link is not a valid URL' => [
            'state' => ['social_links' => ['fakebook' => 'fake.example.com']],
            'errors' => fn () => ['social_links.fakebook' => __('You must enter a valid website address for :key.', ['key' => __('Fakebook')])],
        ],
        'Website link is not a valid URL' => [
            'state' => ['website_link' => 'fake.example.com'],
            'errors' => fn () => ['website_link' => __('validation.active_url', ['attribute' => __('Website link')])],
        ],
    ];
});
