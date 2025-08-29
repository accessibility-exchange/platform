<?php

namespace App\Filament\Resources\ResourceTypes\RelationManagers;

use App\Models\Resource;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'resources';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('author')
                    ->formatStateUsing(fn (string $state, Resource $record): string => $record->authorOrganization ? $record->authorOrganization->name : $state),
                TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn (Resource $record): string => route('filament.admin.resources.resources.edit', $record)),
            ])
            ->toolbarActions([
            ]);
    }
}
