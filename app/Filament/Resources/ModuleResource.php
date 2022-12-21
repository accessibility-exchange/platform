<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuleResource\Pages;
use App\Models\Module;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static ?string $navigationIcon = 'heroicon-s-collection';

    protected static ?string $navigationGroup = 'Training';

    protected static ?int $navigationSort = 2;

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
                Forms\Components\Textarea::make('description.en')
                ->label(__('Module description').' ('.get_language_exonym('en').')')
                    ->requiredWithout('description.fr')
                    ->columnSpan(2),
                Forms\Components\Textarea::make('description.fr')
                ->label(__('Module description').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('description.en')
                    ->columnSpan(2),
                Forms\Components\Textarea::make('introduction.en')
                ->label(__('Module introduction').' ('.get_language_exonym('en').')')
                    ->requiredWithout('introduction.fr')
                    ->columnSpan(2),
                Forms\Components\Textarea::make('introduction.fr')
                ->label(__('Module introduction').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('introduction.en')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date created'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('course.title')
            ->filters([
                Tables\Filters\SelectFilter::make('course')->relationship('course', 'title'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // TODO: Need route from PR #1390
                // Tables\Actions\ViewAction::make()->url(fn (Module $record): string => localized_route('modules.module-content', $record)),
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
            'index' => Pages\ListModules::route('/'),
            'edit' => Pages\EditModule::route('/{record}/edit'),
        ];
    }
}
