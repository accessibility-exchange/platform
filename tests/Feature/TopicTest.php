<?php

use App\Filament\Resources\TopicResource;
use App\Filament\Resources\TopicResource\Pages\ListTopics;
use App\Models\Topic;
use App\Models\User;
use function Pest\Livewire\livewire;

test('only administrative users can access topic admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    $this->actingAs($user)->get(TopicResource::getUrl('index'))->assertForbidden();
    $this->actingAs($administrator)->get(TopicResource::getUrl('index'))->assertSuccessful();

    $this->actingAs($user)->get(TopicResource::getUrl('create'))->assertForbidden();
    $this->actingAs($administrator)->get(TopicResource::getUrl('create'))->assertSuccessful();

    $this->actingAs($user)->get(TopicResource::getUrl('edit', [
        'record' => Topic::factory()->create(),
    ]))->assertForbidden();

    $this->actingAs($administrator)->get(TopicResource::getUrl('edit', [
        'record' => Topic::factory()->create(),
    ]))->assertSuccessful();
});

test('topics can be listed', function () {
    $topics = Topic::factory()->count(2)->create();

    livewire(ListTopics::class)
        ->assertCanSeeTableRecords($topics);
});
