<?php

use App\Models\Page;

use function Pest\Laravel\get;

test('404 if page not created for route', function (string $routeName) {
    get(localized_route($routeName))
        ->assertNotFound();
})->with([
    'Terms of Service' => 'about.terms-of-service',
    'Privacy Policy' => 'about.privacy-policy',
]);

test('Page content rendering', function (string $routeName, string $title, bool $withParam, ?string $content, string $rendered) {
    $page = Page::factory()->create([
        'title' => $title,
        'content' => $content,
    ]);

    $route = $withParam ? localized_route($routeName, $page) : localized_route($routeName);

    get($route)
        ->assertOk()
        ->assertSeeInOrder([
            $page->title,
            $rendered,
        ], false)
        ->assertViewIs('about.show-page');
})->with([
    'Terms of Service' => [
        'routeName' => 'about.terms-of-service',
        'title' => 'Terms of Service',
        'withParam' => false,
    ],
    'Privacy Policy' => [
        'routeName' => 'about.privacy-policy',
        'title' => 'Privacy Policy',
        'withParam' => false,
    ],
    'Test Page' => [
        'routeName' => 'about.page',
        'title' => 'Test Page',
        'withParam' => true,
    ],
])->with([
    'Null content' => [
        'input' => null,
        'output' => 'Coming soon',
    ],
    'Text content' => [
        'input' => 'Text',
        'output' => 'Text',
    ],
    'Markdown content' => [
        'input' => '## Heading',
        'output' => '<h2>Heading</h2>',
    ],
]);

test('ToS contents with interpolated data', function (string $routeName, string $title, bool $withParam = false) {
    $page = Page::factory()->create([
        'title' => $title,
        'content' => '<:home> <:email> [privacy policy](:privacy_policy) :tos',
    ]);

    $route = $withParam ? localized_route($routeName, $page) : localized_route($routeName);

    get($route)
        ->assertOk()
        ->assertSeeInOrder([
            $page->title,
            'href="'.config('app.url').'"',
            'href="mailto:'.settings('email').'"',
            'href="'.localized_route('about.privacy-policy').'"',
            'privacy policy',
            localized_route('about.terms-of-service'),
        ], false);
})->with([
    'Terms of Service' => [
        'routeName' => 'about.terms-of-service',
        'title' => 'Terms of Service',
    ],
    'Privacy Policy' => [
        'routeName' => 'about.privacy-policy',
        'title' => 'Privacy Policy',
    ],
    'Test Page' => [
        'routeName' => 'about.page',
        'title' => 'Test Page',
        'withParam' => true,
    ],
]);
