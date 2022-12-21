<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-s-question-mark-circle';

    protected static ?string $navigationGroup = 'Training';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'question';

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
                Forms\Components\Select::make('quiz_id')
                    ->relationship('quiz', 'title')
                    ->required()
                    ->columnSpan(2),
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
                Tables\Columns\TextColumn::make('question'),
                Tables\Columns\TextColumn::make('quiz.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('choices_count')->counts('choices'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date created'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('quiz.title', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('quiz')->relationship('quiz', 'title'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ChoicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
