<?php

namespace App\Filament\Resources\LibraryResource\RelationManagers;

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
            ->recordTitle(fn (Resource $record): string => $record->getTranslation('title', locale()))
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
