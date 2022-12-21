<?php

namespace App\Filament\Resources\QuizResource\RelationManagers;

use App\Models\Question;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('question.en')
                ->label(__('Question').' ('.get_language_exonym('en').')')
                    ->requiredWithout('question.fr'),
                Forms\Components\TextInput::make('question.fr')
                ->label(__('Question').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('question.en'),
                Forms\Components\TextInput::make('order')
                ->required()
                    ->numeric()
                    ->mask(
                        fn (Forms\Components\TextInput\Mask $mask) => $mask
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                    ),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->sortable(['order', 'question']),
                Tables\Columns\TextColumn::make('choices_count')->counts('choices'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date created'))
                    ->dateTime(),
            ])
            ->defaultSort('question')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn (Question $record): string => route('filament.resources.questions.edit', $record)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
