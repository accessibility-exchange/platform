<?php

use App\Filament\Resources\PageResource;
use App\Filament\Resources\PageResource\Pages\ListPages;
use App\Models\Page;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

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
