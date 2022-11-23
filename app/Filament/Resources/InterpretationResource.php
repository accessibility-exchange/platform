<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InterpretationResource\Pages;
use App\Models\Interpretation;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class InterpretationResource extends Resource
{
    protected static ?string $model = Interpretation::class;

    protected static ?string $navigationIcon = 'tae-sign-language';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('namespace')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('route')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('route_has_params')
                    ->label('Route has parameters')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('video.ase')
                    ->label('ASL Video')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('video.fcs')
                    ->label('LSQ Video')
                    ->url()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('namespace'),
                Tables\Columns\TextColumn::make('route'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
            ])
            ->filters([
                // TODO
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInterpretations::route('/'),
            'create' => Pages\CreateInterpretation::route('/create'),
            'edit' => Pages\EditInterpretation::route('/{record}/edit'),
        ];
    }
}
