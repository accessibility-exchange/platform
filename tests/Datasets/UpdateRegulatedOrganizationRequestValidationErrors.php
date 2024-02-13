<?php

use App\Models\RegulatedOrganization;

dataset('updateRegulatedOrganizationRequestValidationErrors', function () {
    return [
        'Name is missing' => [
            'state' => ['name' => null],
            'errors' => fn () => [
                'name.en' => __('You must enter your organization name.'),
                'name.fr' => __('You must enter your organization name.'),
            ],
        ],
        'Name is missing required translation' => [
            'state' => ['name' => ['es' => 'nombre']],
            'errors' => fn () => [
                'name.en' => __('You must enter your organization name.'),
                'name.fr' => __('You must enter your organization name.'),
            ],
            'without' => ['name'],
        ],
        'Name is not a string' => [
            'state' => ['name' => ['en' => 123]],
            'errors' => fn () => ['name.en' => __('validation.string', ['attribute' => __('organization name (English)')])],
        ],
        'Name is not unique' => [
            'state' => fn () => [
                'name' => RegulatedOrganization::factory()->create(['name' => ['en' => 'english name', 'fr' => 'nom français']])->getTranslations('name'),
            ],
            'errors' => fn () => [
                'name.en' => __('validation.unique', ['attribute' => 'organization name (English)']),
                'name.fr' => __('validation.unique', ['attribute' => 'organization name (French)']),
            ],
        ],
        'Locality is missing' => [
            'state' => ['locality' => null],
            'errors' => fn () => ['locality' => __('validation.required', ['attribute' => __('city or town')])],
        ],
        'Locality is not a string' => [
            'state' => ['locality' => 123],
            'errors' => fn () => ['locality' => __('validation.string', ['attribute' => __('city or town')])],
        ],
        'Region is missing' => [
            'state' => ['region' => null],
            'errors' => fn () => ['region' => __('validation.required', ['attribute' => __('province or territory')])],
        ],
        'Region is invalid' => [
            'state' => ['region' => 'other'],
            'errors' => fn () => ['region' => __('validation.exists', ['attribute' => __('province or territory')])],
        ],
        'Service areas is missing' => [
            'state' => ['service_areas' => null],
            'errors' => fn () => ['service_areas' => __('validation.required', ['attribute' => __('Service areas')])],
        ],
        'Service areas is not an array' => [
            'state' => ['service_areas' => false],
            'errors' => fn () => ['service_areas' => __('validation.array', ['attribute' => __('Service areas')])],
        ],
        'Service area is invalid' => [
            'state' => ['service_areas' => ['xx']],
            'errors' => fn () => ['service_areas.0' => __('validation.exists', ['attribute' => __('Service areas')])],
        ],
        'Sectors is missing' => [
            'state' => ['sectors' => null],
            'errors' => fn () => ['sectors' => __('validation.required', ['attribute' => __('type of Regulated Organization')])],
        ],
        'Sectors is not an array' => [
            'state' => ['sectors' => false],
            'errors' => fn () => ['sectors' => __('validation.array', ['attribute' => __('type of Regulated Organization')])],
        ],
        'Sector is invalid' => [
            'state' => fn () => ['sectors' => [10000000]],
            'errors' => fn () => ['sectors.0' => __('validation.exists', ['attribute' => __('type of Regulated Organization')])],
        ],
        'About is missing' => [
            'state' => ['about' => null],
            'errors' => fn () => [
                'about.en' => __('validation.required_without', ['attribute' => __('“About your organization” (English)'), 'values' => __('“About your organization” (French)')]),
                'about.fr' => __('validation.required_without', ['attribute' => __('“About your organization” (French)'), 'values' => __('“About your organization” (English)')]),
            ],
        ],
        'About is missing required translation' => [
            'state' => ['about' => ['es' => 'acerca de']],
            'errors' => fn () => [
                'about.en' => __('validation.required_without', ['attribute' => __('“About your organization” (English)'), 'values' => __('“About your organization” (French)')]),
                'about.fr' => __('validation.required_without', ['attribute' => __('“About your organization” (French)'), 'values' => __('“About your organization” (English)')]),
            ],
            'without' => ['about'],
        ],
        'About is not a string' => [
            'state' => ['about' => ['en' => 123]],
            'errors' => fn () => ['about.en' => __('validation.string', ['attribute' => __('“About your organization” (English)')])],
        ],
        'Accessibility and inclusion link title is missing' => [
            'state' => ['accessibility_and_inclusion_links' => [
                ['url' => 'https://google.com'],
            ]],
            'errors' => fn () => ['accessibility_and_inclusion_links.0.title' => __('Since a website link under “Accessibility and Inclusion links” has been entered, you must also enter a website title.')],
        ],
        'Accessibility and inclusion link title is not a string' => [
            'state' => ['accessibility_and_inclusion_links' => [
                [
                    'title' => 123,
                    'url' => 'https://google.com',
                ],
            ]],
            'errors' => fn () => ['accessibility_and_inclusion_links.0.title' => __('validation.string', ['attribute' => __('accessibility and inclusion link title')])],
        ],
        'Accessibility and inclusion link url is missing' => [
            'state' => ['accessibility_and_inclusion_links' => [
                ['title' => 'a11y link'],
            ]],
            'errors' => fn () => ['accessibility_and_inclusion_links.0.url' => __('Since a website title under “Accessibility and Inclusion links” has been entered, you must also enter a website link.')],
        ],
        'Accessibility and inclusion link url is not a valid url' => [
            'state' => ['accessibility_and_inclusion_links' => [
                [
                    'title' => 'a11y link',
                    'url' => 'fake.example.com',
                ],
            ]],
            'errors' => fn () => ['accessibility_and_inclusion_links.0.url' => __('Please enter a valid website link under “Accessibility and Inclusion links”.')],
        ],
        'Social link is not a valid url' => [
            'state' => ['social_links' => [
                'fakebook' => 'https://fakebook.example.com',
            ]],
            'errors' => fn () => ['social_links.fakebook' => __('You must enter a valid website address for :key.', ['key' => 'Fakebook'])],
        ],
        'Website link is not a valid url' => [
            'state' => ['website_link' => 'https://fake.example.com'],
            'errors' => fn () => ['website_link' => __('validation.active_url', ['attribute' => __('Website link')])],
        ],
        'Contact person name is missing' => [
            'state' => ['contact_person_name' => null],
            'errors' => fn () => ['contact_person_name' => __('validation.required', ['attribute' => __('Contact person')])],
        ],
        'Contact person name is not a string' => [
            'state' => ['contact_person_name' => 123],
            'errors' => fn () => ['contact_person_name' => __('validation.string', ['attribute' => __('Contact person')])],
        ],
        'Contact person email is missing without phone number' => [
            'state' => ['contact_person_email' => null],
            'errors' => fn () => ['contact_person_email' => __('validation.required_without', ['attribute' => __('email address'), 'values' => __('phone number')])],
            'without' => ['contact_person_phone'],
        ],
        'Contact person email is missing when preferred contact method' => [
            'state' => [
                'contact_person_email' => null,
                'preferred_contact_method' => 'email',
            ],
            'errors' => fn () => ['contact_person_email' => __('validation.required_if', ['attribute' => __('email address'), 'other' => __('preferred contact method'), 'value' => 'email'])],
        ],
        'Contact person email is invalid' => [
            'state' => ['contact_person_email' => 'fake.example.com'],
            'errors' => fn () => ['contact_person_email' => __('validation.email', ['attribute' => __('email address')])],
        ],
        'Contact person phone is missing without email' => [
            'state' => ['contact_person_phone' => null],
            'errors' => fn () => ['contact_person_phone' => __('validation.required_without', ['attribute' => __('phone number'), 'values' => __('email address')])],
            'without' => ['contact_person_email'],
        ],
        'Contact person phone number is missing when preferred contact method' => [
            'state' => [
                'contact_person_phone' => null,
                'preferred_contact_method' => 'phone',
            ],
            'errors' => fn () => ['contact_person_phone' => __('validation.required_if', ['attribute' => __('phone number'), 'other' => __('preferred contact method'), 'value' => 'phone'])],
        ],
        'Contact person phone number is missing when VRS required' => [
            'state' => [
                'contact_person_phone' => null,
                'contact_person_vrs' => true,
            ],
            'errors' => fn () => ['contact_person_phone' => __('Since you have indicated that your contact person needs VRS, please enter a phone number.')],
        ],
        'Contact person email is invalid' => [
            'state' => ['contact_person_phone' => '111-111-1111'],
            'errors' => fn () => ['contact_person_phone' => __('validation.phone', ['attribute' => __('phone number')])],
        ],
        'Contact person VRS is not a boolean' => [
            'state' => ['contact_person_vrs' => 123],
            'errors' => fn () => ['contact_person_vrs' => __('validation.boolean', ['attribute' => __('Contact person requires Video Relay Service (VRS) for phone calls')])],
        ],
        'Preferred contact method is invalid' => [
            'state' => ['preferred_contact_method' => 'other'],
            'errors' => fn () => ['preferred_contact_method' => __('validation.exists', ['attribute' => __('preferred contact method')])],
        ],
        'Preferred contact language is missing' => [
            'state' => ['preferred_contact_language' => null],
            'errors' => fn () => ['preferred_contact_language' => __('validation.required', ['attribute' => __('preferred contact language')])],
        ],
        'Preferred contact language is invalid' => [
            'state' => ['preferred_contact_language' => 'xx'],
            'errors' => fn () => ['preferred_contact_language' => __('validation.exists', ['attribute' => __('preferred contact language')])],
        ],
    ];
});
