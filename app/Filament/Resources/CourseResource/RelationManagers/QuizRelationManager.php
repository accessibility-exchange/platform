<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Models\Quiz;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class QuizRelationManager extends RelationManager
{
    protected static string $relationship = 'quiz';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title.en')
                    ->label(__('Module title').' ('.get_language_exonym('en').')')
                    ->requiredWithout('title.fr'),
                Forms\Components\TextInput::make('title.fr')
                    ->label(__('Module title').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('title.en'),
                Forms\Components\Select::make('course_id')
                    ->relationship('course', 'title')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('minimum_score')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn (Quiz $record): string => route('filament.resources.quizzes.edit', $record)),
            ]);
    }
}
