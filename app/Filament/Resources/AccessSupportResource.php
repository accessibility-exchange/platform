<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessSupportResource\Pages;
use App\Models\AccessSupport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AccessSupportResource extends Resource
{
    protected static ?string $model = AccessSupport::class;

    protected static ?string $navigationIcon = 'heroicon-s-sparkles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name.en')
                    ->label(__('Name (English)'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name.fr')
                    ->label(__('Name (French)'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description.en')
                    ->label(__('Description (English)'))
                    ->columnSpan(2),
                Forms\Components\Textarea::make('description.fr')
                    ->label(__('Description (French)'))
                    ->columnSpan(2),
                Forms\Components\Fieldset::make(__('Access support needed for'))
                    ->schema([
                        Forms\Components\Toggle::make('in_person'),
                        Forms\Components\Toggle::make('virtual'),
                        Forms\Components\Toggle::make('documents'),
                    ]),
                Forms\Components\Toggle::make('anonymizable')
                    ->label(__('Can be supported without disclosing the participant’s identity.')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
                Tables\Columns\IconColumn::make('in_person')
                    ->boolean(),
                Tables\Columns\IconColumn::make('virtual')
                    ->boolean(),
                Tables\Columns\IconColumn::make('documents')
                    ->boolean(),
                Tables\Columns\IconColumn::make('anonymizable')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListAccessSupports::route('/'),
            'create' => Pages\CreateAccessSupport::route('/create'),
            'edit' => Pages\EditAccessSupport::route('/{record}/edit'),
        ];
    }
}
