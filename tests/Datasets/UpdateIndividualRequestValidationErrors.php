<?php

dataset('updateIndividualRequestValidationErrors', function () {
    return [
        'Missing name' => [
            ['name' => null],
            fn () => ['name' => __('validation.required', ['attribute' => 'name'])],
            ['name'],
        ],
        'Name not a string' => [
            ['name' => false],
            fn () => ['name' => __('validation.string', ['attribute' => 'name'])],
        ],
        'Name too long' => [
            ['name' => '4wdjO$bfTeX4m7ya+WTGK10ywy=3tZhfrHnFkx3ZgC8Uyn1a441EjhDw0HqyFm*btGHQneD=q@+bcJEj$owvxR#bsnb+sdm5Xw+a4wdjO$bfTeX4m7ya+WTGK10ywy=3tZhfrHnFkx3ZgC8Uyn1a441EjhDw0HqyFm*btGHQneD=q@+bcJEj$owvxR#bsnb+sdm5Xw+a4wdjO$bfTeX4m7ya+WTGK10ywy=3tZhfrHnFkx3ZgC8Uyn1a441EjhDw0HqyFm*btGHQneD=q@+bcJEj$owvxR#bsnb+sdm5Xw+a'],
            fn () => ['name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255])],
        ],
        'Missing region' => [
            ['region' => null],
            fn () => ['region' => __('validation.required', ['attribute' => __('province or territory')])],
            ['region'],
        ],
        'Invalid region' => [
            ['region' => 'zz'],
            fn () => ['region' => __('validation.in', ['attribute' => __('province or territory')])],
        ],
        'Pronouns translation not an array' => [
            ['pronouns' => 'She'],
            fn () => ['pronouns' => __('Your pronouns must be provided in at least English or French.')],
        ],
        'Invalid pronoun translation' => [
            ['pronouns' => ['es' => 'Ella']],
            fn () => ['pronouns' => __('Your pronouns must be provided in at least English or French.')],
        ],
        'Bio missing' => [
            ['bio' => null],
            fn () => ['bio' => __('validation.required', ['attribute' => 'bio'])],
            ['bio'],
        ],
        'Bio not an array' => [
            ['bio' => 'en'],
            fn () => ['bio' => __('Your bio must be provided in at least English or French.')],
        ],
        'Invalid bio translation' => [
            ['bio' => ['123' => 'test language', 'en' => 'my bio']],
            fn () => ['bio' => __('Your bio must be provided in at least English or French.')],
        ],
        'Bio translation not a string' => [
            ['bio' => ['en' => [123]]],
            fn () => ['bio.en' => __('validation.string', ['attribute' => 'bio.en'])],
        ],
        'Bio missing required translation' => [
            ['bio' => ['es' => 'biografía']],
            fn () => [
                'bio' => __('Your bio must be provided in at least English or French.'),
                'bio.en' => __('Your bio must be provided in at least English or French.'),
                'bio.fr' => __('Your bio must be provided in at least English or French.'),
            ],
            ['bio.en'],
        ],
        'Working languages not an array' => [
            ['working_languages' => 'en'],
            fn () => ['working_languages' => __('validation.array', ['attribute' => 'working languages'])],
        ],
        'Consulting services not an array' => [
            ['consulting_services' => 'analysis'],
            fn () => ['consulting_services' => __('validation.array', ['attribute' => 'consulting services'])],
        ],
        'Consulting service invalid' => [
            ['consulting_services' => ['test-service']],
            fn () => ['consulting_services.0' => __('The selected consulting service is invalid')],
        ],
        'Social link is not an active URL' => [
            ['social_links' => ['Test' => 'https://example.fake/']],
            fn () => ['social_links.Test' => __('You must enter a valid link for :key.', ['key' => 'Test'])],
        ],
        'Website link is not an active URL' => [
            ['website_link' => 'https://example.fake/'],
            fn () => ['website_link' => __('You must enter a valid website link.')],
        ],
    ];
});
