<?php

use App\Enums\UserContext;
use App\Filament\Resources\Tools\Pages\EditTool;
use App\Filament\Resources\Tools\Pages\ListTools;
use App\Filament\Resources\Tools\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\Tools\ToolResource;
use App\Models\Tool;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

test('only administrative users can access tool admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => UserContext::Administrator->value]);
    $tool = Tool::factory()->create();

    actingAs($user)->get(ToolResource::getUrl('index'))->assertForbidden();
    actingAs($administrator)->get(ToolResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(ToolResource::getUrl('create'))->assertForbidden();
    actingAs($administrator)->get(ToolResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(ToolResource::getUrl('edit', [
        'record' => Tool::factory()->create(),
    ]))->assertForbidden();

    actingAs($administrator)->get(ToolResource::getUrl('edit', [
        'record' => Tool::factory()->create(),
    ]))->assertSuccessful();

    actingAs($administrator)->livewire(DocumentsRelationManager::class, [
        'ownerRecord' => $tool,
        'pageClass' => EditTool::class,
    ])
        ->assertSuccessful();
});

test('tools can be listed', function () {
    $tools = Tool::factory()->count(2)->create();

    livewire(ListTools::class)->assertCanSeeTableRecords($tools);
});
