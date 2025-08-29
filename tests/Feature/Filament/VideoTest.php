<?php

use App\Enums\UserContext;
use App\Filament\Resources\Videos\Pages\ListVideos;
use App\Filament\Resources\Videos\VideoResource;
use App\Models\User;
use App\Models\Video;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

test('only administrative users can access video admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => UserContext::Administrator->value]);
    $video = Video::factory()->create();

    actingAs($user)->get(VideoResource::getUrl('index'))->assertForbidden();
    actingAs($administrator)->get(VideoResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(VideoResource::getUrl('create'))->assertForbidden();
    actingAs($administrator)->get(VideoResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(VideoResource::getUrl('edit', [
        'record' => Video::factory()->create(),
    ]))->assertForbidden();

    actingAs($administrator)->get(VideoResource::getUrl('edit', [
        'record' => Video::factory()->create(),
    ]))->assertSuccessful();
});

test('videos can be listed', function () {
    $videos = Video::factory()->count(2)->create();

    livewire(ListVideos::class)->assertCanSeeTableRecords($videos);
});
