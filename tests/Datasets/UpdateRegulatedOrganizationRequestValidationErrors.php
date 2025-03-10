<?php

use App\Models\RegulatedOrganization;

dataset('updateRegulatedOrganizationRequestValidationErrors', function () {
    return [
        'Name is missing' => fn () => [
            'state' => ['name' => null],
            'errors' => [
                'name.en' => __('You must enter your organization name.'),
                'name.fr' => __('You must enter your organization name.'),
            ],
        ],
        'Name is missing required translation' => fn () => [
            'state' => ['name' => ['es' => 'nombre']],
            'errors' => [
                'name.en' => __('You must enter your organization name.'),
                'name.fr' => __('You must enter your organization name.'),
            ],
            'without' => ['name'],
        ],
        'Name is not a string' => fn () => [
            'state' => ['name' => ['en' => 123]],
            'errors' => ['name.en' => __('validation.string', ['attribute' => __('organization name (English)')])],
        ],
        'Name is not unique' => fn () => [
            'state' => [
                'name' => RegulatedOrganization::factory()->create(['name' => ['en' => 'english name', 'fr' => 'nom français']])->getTranslations('name'),
            ],
            'errors' => [
                'name.en' => __('validation.unique', ['attribute' => 'organization name (English)']),
                'name.fr' => __('validation.unique', ['attribute' => 'organization name (French)']),
            ],
        ],
        'Locality is missing' => fn () => [
            'state' => ['locality' => null],
            'errors' => ['locality' => __('validation.required', ['attribute' => __('city or town')])],
        ],
        'Locality is not a string' => fn () => [
            'state' => ['locality' => 123],
            'errors' => ['locality' => __('validation.string', ['attribute' => __('city or town')])],
        ],
        'Region is missing' => fn () => [
            'state' => ['region' => null],
            'errors' => ['region' => __('validation.required', ['attribute' => __('province or territory')])],
        ],
        'Region is invalid' => fn () => [
            'state' => ['region' => 'other'],
            'errors' => ['region' => __('validation.exists', ['attribute' => __('province or territory')])],
        ],
        'Service areas is missing' => fn () => [
            'state' => ['service_areas' => null],
            'errors' => ['service_areas' => __('validation.required', ['attribute' => __('Service areas')])],
        ],
        'Service areas is not an array' => fn () => [
            'state' => ['service_areas' => false],
            'errors' => ['service_areas' => __('validation.array', ['attribute' => __('Service areas')])],
        ],
        'Service area is invalid' => fn () => [
            'state' => ['service_areas' => ['xx']],
            'errors' => ['service_areas.0' => __('validation.exists', ['attribute' => __('Service areas')])],
        ],
        'Sectors is missing' => fn () => [
            'state' => ['sectors' => null],
            'errors' => ['sectors' => __('validation.required', ['attribute' => __('type of Regulated Organization')])],
        ],
        'Sectors is not an array' => fn () => [
            'state' => ['sectors' => false],
            'errors' => ['sectors' => __('validation.array', ['attribute' => __('type of Regulated Organization')])],
        ],
        'Sector is invalid' => fn () => [
            'state' => ['sectors' => [10000000]],
            'errors' => ['sectors.0' => __('validation.exists', ['attribute' => __('type of Regulated Organization')])],
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
            'state' => ['about' => ['en' => 123]],
            'errors' => ['about.en' => __('validation.string', ['attribute' => __('“About your organization” (English)')])],
        ],
        'Accessibility and inclusion link title is missing' => fn () => [
            'state' => ['accessibility_and_inclusion_links' => [
                ['url' => 'https://google.com'],
            ]],
            'errors' => ['accessibility_and_inclusion_links.0.title' => __('Since a website link under “Accessibility and Inclusion links” has been entered, you must also enter a website title.')],
        ],
        'Accessibility and inclusion link title is not a string' => fn () => [
            'state' => ['accessibility_and_inclusion_links' => [
                [
                    'title' => 123,
                    'url' => 'https://google.com',
                ],
            ]],
            'errors' => ['accessibility_and_inclusion_links.0.title' => __('validation.string', ['attribute' => __('accessibility and inclusion link title')])],
        ],
        'Accessibility and inclusion link url is missing' => fn () => [
            'state' => ['accessibility_and_inclusion_links' => [
                ['title' => 'a11y link'],
            ]],
            'errors' => ['accessibility_and_inclusion_links.0.url' => __('Since a website title under “Accessibility and Inclusion links” has been entered, you must also enter a website link.')],
        ],
        'Accessibility and inclusion link url is not a valid url' => fn () => [
            'state' => ['accessibility_and_inclusion_links' => [
                [
                    'title' => 'a11y link',
                    'url' => 'fake.example.com',
                ],
            ]],
            'errors' => ['accessibility_and_inclusion_links.0.url' => __('Please enter a valid website link under “Accessibility and Inclusion links”.')],
        ],
        'Social link is not a valid url' => fn () => [
            'state' => ['social_links' => [
                'fakebook' => 'https://fakebook.example.com',
            ]],
            'errors' => ['social_links.fakebook' => __('You must enter a valid website address for :key.', ['key' => 'Fakebook'])],
        ],
        'Website link is not a valid url' => fn () => [
            'state' => ['website_link' => 'https://fake.example.com'],
            'errors' => ['website_link' => __('validation.active_url', ['attribute' => __('Website link')])],
        ],
        'Contact person name is missing' => fn () => [
            'state' => ['contact_person_name' => null],
            'errors' => ['contact_person_name' => __('validation.required', ['attribute' => __('Contact person')])],
        ],
        'Contact person name is not a string' => fn () => [
            'state' => ['contact_person_name' => 123],
            'errors' => ['contact_person_name' => __('validation.string', ['attribute' => __('Contact person')])],
        ],
        'Contact person email is missing without phone number' => fn () => [
            'state' => ['contact_person_email' => null],
            'errors' => ['contact_person_email' => __('validation.required_without', ['attribute' => __('email address'), 'values' => __('phone number')])],
            'without' => ['contact_person_phone'],
        ],
        'Contact person email is missing when preferred contact method' => fn () => [
            'state' => [
                'contact_person_email' => null,
                'preferred_contact_method' => 'email',
            ],
            'errors' => ['contact_person_email' => __('validation.required_if', ['attribute' => __('email address'), 'other' => __('preferred contact method'), 'value' => 'email'])],
        ],
        'Contact person email is invalid' => fn () => [
            'state' => ['contact_person_email' => 'fake.example.com'],
            'errors' => ['contact_person_email' => __('validation.email', ['attribute' => __('email address')])],
        ],
        'Contact person phone is missing without email' => fn () => [
            'state' => ['contact_person_phone' => null],
            'errors' => ['contact_person_phone' => __('validation.required_without', ['attribute' => __('phone number'), 'values' => __('email address')])],
            'without' => ['contact_person_email'],
        ],
        'Contact person phone number is missing when preferred contact method' => fn () => [
            'state' => [
                'contact_person_phone' => null,
                'preferred_contact_method' => 'phone',
            ],
            'errors' => ['contact_person_phone' => __('validation.required_if', ['attribute' => __('phone number'), 'other' => __('preferred contact method'), 'value' => 'phone'])],
        ],
        'Contact person phone number is missing when VRS required' => fn () => [
            'state' => [
                'contact_person_phone' => null,
                'contact_person_vrs' => true,
            ],
            'errors' => ['contact_person_phone' => __('Since you have indicated that your contact person needs VRS, please enter a phone number.')],
        ],
        'Contact person email is invalid' => fn () => [
            'state' => ['contact_person_phone' => '111-111-1111'],
            'errors' => ['contact_person_phone' => __('validation.phone', ['attribute' => __('phone number')])],
        ],
        'Contact person VRS is not a boolean' => fn () => [
            'state' => ['contact_person_vrs' => 123],
            'errors' => ['contact_person_vrs' => __('validation.boolean', ['attribute' => __('Contact person requires Video Relay Service (VRS) for phone calls')])],
        ],
        'Preferred contact method is invalid' => fn () => [
            'state' => ['preferred_contact_method' => 'other'],
            'errors' => ['preferred_contact_method' => __('validation.exists', ['attribute' => __('preferred contact method')])],
        ],
        'Preferred contact language is missing' => fn () => [
            'state' => ['preferred_contact_language' => null],
            'errors' => ['preferred_contact_language' => __('validation.required', ['attribute' => __('preferred contact language')])],
        ],
        'Preferred contact language is invalid' => fn () => [
            'state' => ['preferred_contact_language' => 'xx'],
            'errors' => ['preferred_contact_language' => __('validation.exists', ['attribute' => __('preferred contact language')])],
        ],
    ];
});
