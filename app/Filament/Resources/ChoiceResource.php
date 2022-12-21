<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChoiceResource\Pages;
use App\Models\Choice;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ChoiceResource extends Resource
{
    protected static ?string $model = Choice::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label.en')
                    ->label(__('Choice').' ('.get_language_exonym('en').')')
                    ->requiredWithout('label.fr'),
                Forms\Components\TextInput::make('label.fr')
                    ->label(__('Choice').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('label.en'),
                Forms\Components\Select::make('question_id')
                    ->relationship('question', 'question')
                    ->required(),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_answer')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question.id'),
                Tables\Columns\TextColumn::make('value'),
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\IconColumn::make('is_answer')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
            'index' => Pages\ListChoices::route('/'),
            'edit' => Pages\EditChoice::route('/{record}/edit'),
        ];
    }
}
