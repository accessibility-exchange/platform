<?php

use App\Models\Organization;

dataset('updateOrganizationRequestValidationErrors', function () {
    return [
        'Name is missing' => fn () => [
            'state' => ['name' => null],
            'errors' => [
                'name.en' => __('You must enter your organization name.'),
                'name.fr' => __('You must enter your organization name.'),
            ],
        ],
        'Name is not unique' => fn () => [
            'state' => ['name' => Organization::factory()->create(['name' => ['en' => 'english name', 'fr' => 'nom français']])->getTranslations('name')],
            'errors' => [
                'name.en' => __('validation.unique', ['attribute' => __('organization name (English)')]),
                'name.fr' => __('validation.unique', ['attribute' => __('organization name (French)')]),
            ],
        ],
        'Name is not a string' => fn () => [
            'state' => ['name' => ['en' => 123, 'fr' => false]],
            'errors' => [
                'name.en' => __('validation.string', ['attribute' => __('organization name (English)')]),
                'name.fr' => __('validation.string', ['attribute' => __('organization name (French)')]),
            ],
        ],
        'About is missing' => fn () => [
            'state' => ['about' => null],
            'errors' => [
                'about.en' => __('“About your organization” must be provided in either English or French.'),
                'about.fr' => __('“About your organization” must be provided in either English or French.'),
            ],
        ],
        'About is missing required translation' => fn () => [
            'state' => ['about' => ['es' => 'acerca de']],
            'errors' => [
                'about.en' => __('“About your organization” must be provided in either English or French.'),
                'about.fr' => __('“About your organization” must be provided in either English or French.'),
            ],
            'without' => ['about'],
        ],
        'About is not a string' => fn () => [
            'state' => ['about' => ['en' => [], 'fr' => false]],
            'errors' => [
                'about.en' => __('validation.string', ['attribute' => __('“About your organization” (English)')]),
                'about.fr' => __('validation.string', ['attribute' => __('“About your organization” (French)')]),
            ],
        ],
        'Region is missing' => fn () => [
            'state' => ['region' => null],
            'errors' => ['region' => __('validation.required', ['attribute' => __('province or territory')])],
        ],
        'Region is invalid' => fn () => [
            'state' => ['region' => 'yyz'],
            'errors' => ['region' => __('validation.exists', ['attribute' => __('province or territory')])],
        ],
        'Locality is missing' => fn () => [
            'state' => ['locality' => null],
            'errors' => ['locality' => __('validation.required', ['attribute' => __('city or town')])],
        ],
        'Locality is not a string' => fn () => [
            'state' => ['locality' => 123],
            'errors' => ['locality' => __('validation.string', ['attribute' => __('city or town')])],
        ],
        'Service areas is missing' => fn () => [
            'state' => ['service_areas' => null],
            'errors' => ['service_areas' => __('validation.required', ['attribute' => __('Service areas')])],
        ],
        'Service areas is not an array' => fn () => [
            'state' => ['service_areas' => 123],
            'errors' => ['service_areas' => __('validation.array', ['attribute' => __('Service areas')])],
        ],
        'Service area is invalid' => fn () => [
            'state' => ['service_areas' => ['yyz']],
            'errors' => ['service_areas.0' => __('validation.exists', ['attribute' => __('Service areas')])],
        ],
        'Working languages is missing' => fn () => [
            'state' => ['working_languages' => null],
            'errors' => ['working_languages' => __('validation.required', ['attribute' => __('Working languages')])],
        ],
        'Working languages is not an array' => fn () => [
            'state' => ['working_languages' => 123],
            'errors' => ['working_languages' => __('validation.array', ['attribute' => __('Working languages')])],
        ],
        'Consulting services is missing (org is accessibility consultant)' => fn () => [
            'state' => ['consulting_services' => null],
            'errors' => ['consulting_services' => __('validation.required', ['attribute' => __('Consulting services')])],
        ],
        'Consulting services is not an array' => fn () => [
            'state' => ['consulting_services' => 123],
            'errors' => ['consulting_services' => __('validation.array', ['attribute' => __('Consulting services')])],
        ],
        'Consulting service is invalid' => fn () => [
            'state' => ['consulting_services' => ['other']],
            'errors' => ['consulting_services.0' => __('validation.exists', ['attribute' => __('Consulting services')])],
        ],
        'Social link is not a valid URL' => fn () => [
            'state' => ['social_links' => ['fakebook' => 'fake.example.com']],
            'errors' => ['social_links.fakebook' => __('You must enter a valid website address for :key.', ['key' => __('Fakebook')])],
        ],
        'Website link is not a valid URL' => fn () => [
            'state' => ['website_link' => 'fake.example.com'],
            'errors' => ['website_link' => __('validation.active_url', ['attribute' => __('Website link')])],
        ],
    ];
});
