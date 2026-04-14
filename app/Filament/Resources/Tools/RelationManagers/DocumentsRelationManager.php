<?php

namespace App\Filament\Resources\Tools\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([])
            ->headerActions([
                AssociateAction::make()
                    ->preloadRecordSelect()
                    ->associateAnother(false)
                    ->recordSelectOptionsQuery(fn ($query) => $query->orderBy('created_at', 'desc'))
                    ->forceSearchCaseInsensitive(),
            ])
            ->recordActions([
                DissociateAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                ]),
            ]);
    }
}
