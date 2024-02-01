<?php

namespace App\Filament\Resources\LanguageResource\Pages;

use App\Filament\Resources\LanguageResource;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;

class CreateLanguage extends CreateRecord
{
    protected static string $resource = LanguageResource::class;

    protected static ?string $title = 'Create User Language';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('code')
                    ->label(__('Language'))
                    ->options(Arr::except(get_available_languages(true), Language::pluck('code')->all()))
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, ?string $state, ?string $old) {
                        $set('name.en', get_language_exonym($state ?? '', 'en'));
                        $set('name.fr', get_language_exonym($state ?? '', 'fr'));
                    })
                    ->required(),
                Forms\Components\Placeholder::make('code_display')
                    ->label(__('Language code'))
                    ->content(fn (Forms\Get $get): string => $get('code') ?? ''),
                Forms\Components\TextInput::make('name.en')
                    ->label(__('Name (English)'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name.fr')
                    ->label(__('Name (French)'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['name']['asl'] = $data['name']['en'];
        $data['name']['lsq'] = $data['name']['fr'];

        return $data;
    }
}
