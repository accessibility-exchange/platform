<?php

namespace App\Filament\Resources\Languages\Pages;

use App\Filament\Resources\Languages\LanguageResource;
use App\Models\Language;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;

class CreateLanguage extends CreateRecord
{
    protected static string $resource = LanguageResource::class;

    protected static ?string $title = 'Create User Language';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('code')
                    ->label(__('Language'))
                    ->options(Arr::except(get_available_languages(true), Language::pluck('code')->all()))
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state, ?string $old) {
                        $set('name.en', get_language_exonym($state ?? '', 'en'));
                        $set('name.fr', get_language_exonym($state ?? '', 'fr'));
                    })
                    ->required(),
                Placeholder::make('code_display')
                    ->label(__('Language code'))
                    ->content(fn (Get $get): string => $get('code') ?? ''),
                TextInput::make('name.en')
                    ->label(__('Name (English)'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('name.fr')
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
