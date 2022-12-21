<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-s-academic-cap';

    protected static ?string $navigationGroup = 'Training';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title.en')
                    ->label(__('Course title').' ('.get_language_exonym('en').')')
                    ->requiredWithout('title.fr'),
                Forms\Components\TextInput::make('title.fr')
                    ->label(__('Course title').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('title.en'),
                Forms\Components\Select::make('organization_id')
                    ->label(__('Author organization'))
                    ->relationship('organization', 'name')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('video.en')
                    ->label(__('Video link').' ('.get_language_exonym('en').')')
                    ->activeUrl()
                    ->url()
                    ->requiredWithout('video.fr'),
                Forms\Components\TextInput::make('video.fr')
                    ->label(__('Video link').' ('.get_language_exonym('fr').')')
                    ->activeUrl()
                    ->url()
                    ->requiredWithout('video.en'),
                Forms\Components\Textarea::make('introduction.en')
                    ->label(__('Course introduction').' ('.get_language_exonym('en').')')
                    ->requiredWithout('introduction.fr')
                    ->columnSpan(2),
                Forms\Components\Textarea::make('introduction.fr')
                    ->label(__('Course introduction').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('introduction.en')
                    ->columnSpan(2),

                Forms\Components\Fieldset::make('quiz')
                    ->label(__('Quiz'))
                    ->relationship('quiz')
                    ->schema([
                        Forms\Components\TextInput::make('title.en')
                            ->label(__('Module title').' ('.get_language_exonym('en').')')
                            ->requiredWithout('title.fr')
                            ->reactive(),
                        Forms\Components\TextInput::make('title.fr')
                            ->label(__('Module title').' ('.get_language_exonym('fr').')')
                            ->requiredWithout('title.en')
                            ->reactive(),
                        Forms\Components\TextInput::make('minimum_score')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->reactive(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                // TODO: Make this a count of users who have completed the course. Requires PR #1390
                Tables\Columns\TextColumn::make('usersCompleted')
                    ->formatStateUsing(fn (?string $state, Course $record): int => $record->users->count()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date created'))
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // TODO: Need route from PR #1390
                // Tables\Actions\ViewAction::make()->url(fn (Course $record): string => localized_route('courses.show', $record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    // TODO: add a relationship to users to show who has completed the course
    public static function getRelations(): array
    {
        return [
            RelationManagers\ModulesRelationManager::class,
            RelationManagers\QuizRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
