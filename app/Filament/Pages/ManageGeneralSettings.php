<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;

class ManageGeneralSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationLabel = 'Website settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $slug = 'settings';

    protected static string $settings = GeneralSettings::class;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->label(__('Support email'))
                ->columnSpan('full')
                ->required()
                ->email(),
            TextInput::make('phone')
                ->label(__('Support phone'))
                ->columnSpan('full')
                ->required(),
            Textarea::make('address')
                ->label(__('Mailing address'))
                ->columnSpan('full')
                ->required(),
            TextInput::make('facebook')
                ->label(__('Facebook page'))
                ->columnSpan('full')
                ->required()
                ->activeUrl(),
            TextInput::make('linkedin')
                ->label(__('LinkedIn page'))
                ->columnSpan('full')
                ->required()
                ->activeUrl(),
            TextInput::make('twitter')
                ->label(__('Twitter page'))
                ->columnSpan('full')
                ->required()
                ->activeUrl(),
            TextInput::make('youtube')
                ->label(__('YouTube page'))
                ->columnSpan('full')
                ->required()
                ->activeUrl(),
        ];
    }
}
