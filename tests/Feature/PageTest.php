<?php

use App\Filament\Resources\PageResource;
use App\Filament\Resources\PageResource\Pages\ListPages;
use App\Models\Page;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

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

test('only site admins users can access Page admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);
    $page = Page::factory()->create();

    actingAs($user)->get(PageResource::getUrl('index'))->assertForbidden();
    actingAs($administrator)->get(PageResource::getUrl('index'))->assertSuccessful();

    // Creation is disabled for all users
    actingAs($user)->get(PageResource::getUrl('create'))->assertForbidden();
    actingAs($administrator)->get(PageResource::getUrl('create'))->assertForbidden();

    actingAs($user)->get(PageResource::getUrl('edit', [
        'record' => Page::factory()->create(),
    ]))->assertForbidden();

    actingAs($administrator)->get(PageResource::getUrl('edit', [
        'record' => Page::factory()->create(),
    ]))->assertSuccessful();
});

test('Pages can be listed in the admin panel', function () {
    $pages = Page::factory(2)->create();

    livewire(ListPages::class)
        ->assertCanSeeTableRecords($pages)
        ->assertSee($pages[0]->title)
        ->assertSee(localized_route('about.page', $pages[0]))
        ->assertSee(PageResource::getUrl('edit', ['record' => $pages[0]]))
        ->assertSee($pages[1]->title)
        ->assertSee(localized_route('about.page', $pages[1]))
        ->assertSee(PageResource::getUrl('edit', ['record' => $pages[1]]));
});
