<?php

use App\Enums\UserContext;
use App\Filament\Resources\Interpretations\InterpretationResource;
use App\Filament\Resources\Interpretations\Pages\ListInterpretations;
use App\Models\Interpretation;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

test('only administrative users can access interpretation admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => UserContext::Administrator->value]);

    actingAs($user)->get(InterpretationResource::getUrl('index'))->assertForbidden();
    actingAs($administrator)->get(InterpretationResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(InterpretationResource::getUrl('create'))->assertForbidden();
    actingAs($administrator)->get(InterpretationResource::getUrl('create'))->assertForbidden();

    actingAs($user)->get(InterpretationResource::getUrl('edit', [
        'record' => Interpretation::factory()->create(),
    ]))->assertForbidden();

    actingAs($administrator)->get(InterpretationResource::getUrl('edit', [
        'record' => Interpretation::factory()->create(),
    ]))->assertSuccessful();
});

test('interpretations can be listed', function () {
    $interpretationsWithVideos = Interpretation::factory()->count(2)->create();
    $interpretationsWithoutVideos = Interpretation::factory()->count(2)->create(['video' => ['lsq' => '', 'asl' => '']]);

    livewire(ListInterpretations::class)
        ->assertCanSeeTableRecords($interpretationsWithVideos);

    livewire(ListInterpretations::class)
        ->assertCanSeeTableRecords($interpretationsWithoutVideos);
});
