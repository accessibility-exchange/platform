<?php

namespace App\Filament\Resources\Impacts;

use App\Filament\Resources\Impacts\Pages\CreateImpact;
use App\Filament\Resources\Impacts\Pages\EditImpact;
use App\Filament\Resources\Impacts\Pages\ListImpacts;
use App\Models\Impact;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImpactResource extends Resource
{
    protected static ?string $model = Impact::class;

    protected static ?string $navigationLabel = 'Areas of Accessibility Planning';

    protected static ?int $navigationSort = 2;

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
                Textarea::make('description.en')
                    ->label(__('Description (English)'))
                    ->columnSpan(2),
                Textarea::make('description.fr')
                    ->label(__('Description (French)'))
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable(),
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
                EditAction::make()->tooltip(fn (Impact $record): string => __('Edit :name', ['name' => $record->name])),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->paginated([10, 25, 50, 'all']);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListImpacts::route('/'),
            'create' => CreateImpact::route('/create'),
            'edit' => EditImpact::route('/{record}/edit'),
        ];
    }
}
