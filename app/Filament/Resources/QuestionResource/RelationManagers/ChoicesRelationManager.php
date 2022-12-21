<?php

namespace App\Filament\Resources\QuestionResource\RelationManagers;

use App\Models\Choice;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ChoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'choices';

    protected static ?string $recordTitleAttribute = 'choice';

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
                Tables\Columns\TextColumn::make('label')
                    ->label(__('Choice')),
                Tables\Columns\IconColumn::make('is_answer')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date created'))
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn (Choice $record): string => route('filament.resources.choices.edit', $record)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
