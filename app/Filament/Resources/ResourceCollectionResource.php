<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResourceCollectionResource\Pages;
use App\Models\ResourceCollection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ResourceCollectionResource extends Resource
{
    protected static ?string $model = ResourceCollection::class;

    protected static ?string $navigationIcon = 'heroicon-m-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title.en')
                    ->label(__('Resource collection title').' ('.get_language_exonym('en').')')
                    ->requiredWithout('title.fr'),
                Forms\Components\TextInput::make('title.fr')
                    ->label(__('Resource collection title').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('title.en'),
                Forms\Components\MarkdownEditor::make('description.en')
                    ->toolbarButtons(['bold', 'italic', 'edit', 'preview'])
                    ->label(__('Description').' ('.get_language_exonym('en').')')
                    ->columnSpan(2),
                Forms\Components\MarkdownEditor::make('description.fr')
                    ->toolbarButtons(['bold', 'italic', 'edit', 'preview'])
                    ->label(__('Description').' ('.get_language_exonym('fr').')')
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('resourceCount')
                    ->formatStateUsing(fn (?string $state, ResourceCollection $record): int => $record->resources->count()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date created'))
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()->url(fn (ResourceCollection $record): string => localized_route('resource-collections.show', $record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ResourceCollectionResource\RelationManagers\ResourcesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResourceCollections::route('/'),
            'create' => Pages\CreateResourceCollection::route('/create'),
            'edit' => Pages\EditResourceCollection::route('/{record}/edit'),
        ];
    }
}
