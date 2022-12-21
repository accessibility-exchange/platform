<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-text';

    protected static ?string $navigationGroup = 'Training';

    protected static ?int $navigationSort = 3;

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
                    ->mask(
                        fn (Forms\Components\TextInput\Mask $mask) => $mask
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.title')
                    ->sortable(['title']),
                Tables\Columns\TextColumn::make('minimum_score'),
                Tables\Columns\TextColumn::make('questions_count')->counts('questions'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date created'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('title')
            ->filters([
                Tables\Filters\SelectFilter::make('course')->relationship('course', 'title'),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}
