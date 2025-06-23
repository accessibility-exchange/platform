<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Resources\DocumentResource\RelationManagers;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name.en')
                    ->label(__('Document name').' ('.get_language_exonym('en').')')
                    ->requiredWithout('name.fr')
                    ->maxLength(255),
                Forms\Components\TextInput::make('name.fr')
                    ->label(__('Document name').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('name.en')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description.en')
                    ->label(__('Description').' ('.get_language_exonym('en').')')
                    ->columnSpan(2),
                Forms\Components\Textarea::make('description.fr')
                    ->label(__('Description').' ('.get_language_exonym('fr').')')
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('revisions_count')
                    ->label(__('Revisions'))
                    ->counts('revisions'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RevisionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
