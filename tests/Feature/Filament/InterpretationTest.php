<?php

use App\Filament\Resources\InterpretationResource;
use App\Models\Interpretation;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

test('only administrative users can access interpretation admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

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

    livewire(InterpretationResource\Pages\ListInterpretations::class)
        ->assertCanSeeTableRecords($interpretationsWithVideos);

    livewire(InterpretationResource\Pages\ListInterpretations::class)
        ->assertCanSeeTableRecords($interpretationsWithoutVideos);
});
