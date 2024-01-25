<?php

use App\Filament\Resources\TopicResource;
use App\Filament\Resources\TopicResource\Pages\ListTopics;
use App\Models\Topic;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

test('only administrative users can access topic admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

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
