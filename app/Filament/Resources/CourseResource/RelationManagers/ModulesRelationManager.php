<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Models\Module;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'modules';

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

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('title')
    //                 ->required()
    //                 ->maxLength(255),
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn (Module $record): string => route('filament.resources.modules.edit', $record)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
