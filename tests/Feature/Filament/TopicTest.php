<?php

use App\Enums\UserContext;
use App\Filament\Resources\Topics\Pages\ListTopics;
use App\Filament\Resources\Topics\TopicResource;
use App\Models\Topic;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

test('only administrative users can access topic admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => UserContext::Administrator->value]);

    actingAs($user)->get(TopicResource::getUrl('index'))->assertForbidden();
    actingAs($administrator)->get(TopicResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(TopicResource::getUrl('create'))->assertForbidden();
    actingAs($administrator)->get(TopicResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(TopicResource::getUrl('edit', [
        'record' => Topic::factory()->create(),
    ]))->assertForbidden();

    actingAs($administrator)->get(TopicResource::getUrl('edit', [
        'record' => Topic::factory()->create(),
    ]))->assertSuccessful();
});

test('topics can be listed', function () {
    $topics = Topic::factory()->count(2)->create();

    livewire(ListTopics::class)
        ->assertCanSeeTableRecords($topics);
});
