<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImpactResource\Pages;
use App\Models\Impact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ImpactResource extends Resource
{
    protected static ?string $model = Impact::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
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
            'index' => Pages\ListImpacts::route('/'),
            'create' => Pages\CreateImpact::route('/create'),
            'edit' => Pages\EditImpact::route('/{record}/edit'),
        ];
    }
}
