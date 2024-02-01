<?php

namespace App\Filament\Resources\ContentTypeResource\RelationManagers;

use App\Models\Resource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ResourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'resources';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('author')
                    ->formatStateUsing(fn (string $state, Resource $record): string => $record->authorOrganization ? $record->authorOrganization->name : $state),
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn (Resource $record): string => route('filament.admin.resources.resources.edit', $record)),
            ])
            ->bulkActions([
            ]);
    }
}
