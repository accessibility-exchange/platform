<?php

namespace App\Filament\Resources\AccessSupports;

use App\Filament\Resources\AccessSupports\Pages\CreateAccessSupport;
use App\Filament\Resources\AccessSupports\Pages\EditAccessSupport;
use App\Filament\Resources\AccessSupports\Pages\ListAccessSupports;
use App\Models\AccessSupport;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AccessSupportResource extends Resource
{
    protected static ?string $model = AccessSupport::class;

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
                Fieldset::make(__('Access support needed for'))
                    ->schema([
                        Toggle::make('in_person'),
                        Toggle::make('virtual'),
                        Toggle::make('documents'),
                    ]),
                Toggle::make('anonymizable')
                    ->label(__('Can be supported without disclosing the participant’s identity.')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable(),
                IconColumn::make('in_person')
                    ->boolean(),
                IconColumn::make('virtual')
                    ->boolean(),
                IconColumn::make('documents')
                    ->boolean(),
                IconColumn::make('anonymizable')
                    ->boolean(),
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
                EditAction::make()->tooltip(fn (AccessSupport $record): string => __('Edit :name', ['name' => $record->name])),
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
            'index' => ListAccessSupports::route('/'),
            'create' => CreateAccessSupport::route('/create'),
            'edit' => EditAccessSupport::route('/{record}/edit'),
        ];
    }
}
