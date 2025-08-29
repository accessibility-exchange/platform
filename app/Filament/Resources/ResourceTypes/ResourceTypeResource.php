<?php

namespace App\Filament\Resources\ResourceTypes;

use App\Filament\Resources\ResourceTypes\Pages\CreateResourceType;
use App\Filament\Resources\ResourceTypes\Pages\EditResourceType;
use App\Filament\Resources\ResourceTypes\Pages\ListResourceTypes;
use App\Filament\Resources\ResourceTypes\RelationManagers\ResourcesRelationManager;
use App\Models\ResourceType;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResourceTypeResource extends Resource
{
    protected static ?string $model = ResourceType::class;

    protected static ?int $navigationSort = 3;

    protected static string|\UnitEnum|null $navigationGroup = 'Metadata';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name.en')
                    ->label(__('Name (English)'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('name.fr')
                    ->label(__('Name (French)'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('created_at')
                    ->label(__('Date added'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Date modified'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->tooltip(fn (ResourceType $record): string => __('Edit :name', ['name' => $record->name])),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->paginated([10, 25, 50, 'all']);
    }

    public static function getRelations(): array
    {
        return [
            ResourcesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResourceTypes::route('/'),
            'create' => CreateResourceType::route('/create'),
            'edit' => EditResourceType::route('/{record}/edit'),
        ];
    }
}
