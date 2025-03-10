<?php

use App\Enums\ConsultingService;

dataset('updateIndividualRequestValidationErrors', function () {
    return [
        'Missing name' => fn () => [
            'state' => ['name' => null],
            'errors' => ['name' => __('validation.required', ['attribute' => __('full name')])],
            'without' => ['name'],
        ],
        'Name not a string' => fn () => [
            'state' => ['name' => false],
            'errors' => ['name' => __('validation.string', ['attribute' => __('full name')])],
        ],
        'Name too long' => fn () => [
            'state' => ['name' => '4wdjO$bfTeX4m7ya+WTGK10ywy=3tZhfrHnFkx3ZgC8Uyn1a441EjhDw0HqyFm*btGHQneD=q@+bcJEj$owvxR#bsnb+sdm5Xw+a4wdjO$bfTeX4m7ya+WTGK10ywy=3tZhfrHnFkx3ZgC8Uyn1a441EjhDw0HqyFm*btGHQneD=q@+bcJEj$owvxR#bsnb+sdm5Xw+a4wdjO$bfTeX4m7ya+WTGK10ywy=3tZhfrHnFkx3ZgC8Uyn1a441EjhDw0HqyFm*btGHQneD=q@+bcJEj$owvxR#bsnb+sdm5Xw+a'],
            'errors' => ['name' => __('validation.max.string', ['attribute' => __('full name'), 'max' => 255])],
        ],
        'Missing region' => fn () => [
            'state' => ['region' => null],
            'errors' => ['region' => __('validation.required', ['attribute' => __('province or territory')])],
            'without' => ['region'],
        ],
        'Invalid region' => fn () => [
            'state' => ['region' => 'zz'],
            'errors' => ['region' => __('validation.in', ['attribute' => __('province or territory')])],
        ],
        'Pronouns translation not an array' => fn () => [
            'state' => ['pronouns' => 'She'],
            'errors' => ['pronouns' => __('Your pronouns must be provided in either English or French.')],
        ],
        'Invalid pronoun translation' => fn () => [
            'state' => ['pronouns' => ['es' => 'Ella']],
            'errors' => ['pronouns' => __('Your pronouns must be provided in either English or French.')],
        ],
        'Bio missing' => fn () => [
            'state' => ['bio' => null],
            'errors' => ['bio' => __('validation.required', ['attribute' => __('bio')])],
            'without' => ['bio'],
        ],
        'Bio not an array' => fn () => [
            'state' => ['bio' => 'en'],
            'errors' => ['bio' => __('Your bio must be provided in either English or French.')],
        ],
        'Invalid bio translation' => fn () => [
            'state' => ['bio' => ['123' => 'test language', 'en' => 'my bio']],
            'errors' => ['bio' => __('Your bio must be provided in either English or French.')],
        ],
        'Bio translation not a string' => fn () => [
            'state' => ['bio' => ['en' => [123]]],
            'errors' => ['bio.en' => __('validation.string', ['attribute' => __('bio (English)')])],
        ],
        'Bio missing required translation' => fn () => [
            'state' => ['bio' => ['es' => 'biografía']],
            'errors' => [
                'bio' => __('Your bio must be provided in either English or French.'),
                'bio.en' => __('Your bio must be provided in either English or French.'),
                'bio.fr' => __('Your bio must be provided in either English or French.'),
            ],
            'without' => ['bio.en'],
        ],
        'Working languages not an array' => fn () => [
            'state' => ['working_languages' => 'en'],
            'errors' => ['working_languages' => __('validation.array', ['attribute' => __('Working languages')])],
        ],
        'Consulting services not an array' => fn () => [
            'state' => ['consulting_services' => ConsultingService::Analysis->value],
            'errors' => ['consulting_services' => __('validation.array', ['attribute' => __('Consulting services')])],
        ],
        'Consulting service invalid' => fn () => [
            'state' => ['consulting_services' => ['test-service']],
            'errors' => ['consulting_services.0' => __('The selected consulting service is invalid')],
        ],
        'Social link is not an active URL' => fn () => [
            'state' => ['social_links' => ['Test' => 'https://example.fake/']],
            'errors' => ['social_links.Test' => __('You must enter a valid link for :key.', ['key' => 'Test'])],
        ],
        'Website link is not an active URL' => fn () => [
            'state' => ['website_link' => 'https://example.fake/'],
            'errors' => ['website_link' => __('You must enter a valid website link.')],
        ],
    ];
});
