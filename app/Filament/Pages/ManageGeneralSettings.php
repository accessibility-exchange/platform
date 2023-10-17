<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageGeneralSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationLabel = 'Website settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $slug = 'settings';

    protected static string $settings = GeneralSettings::class;

    public static ?string $title = 'Website settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Contact'))
                    ->schema([
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
                    ]),
                Section::make(__('Social media'))
                    ->schema([
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
                    ]),
                Section::make(__('Registration'))
                    ->schema([
                        TextInput::make('individual_orientation')
                            ->label(__('Individual orientation'))
                            ->columnSpan('full')
                            ->required()
                            ->activeUrl(),
                        TextInput::make('org_orientation')
                            ->label(__('Community organization orientation'))
                            ->columnSpan('full')
                            ->required()
                            ->activeUrl(),
                        TextInput::make('fro_orientation')
                            ->label(__('Federally regulated organization orientation'))
                            ->columnSpan('full')
                            ->required()
                            ->activeUrl(),
                        TextInput::make('ac_application')
                            ->label(__('Accessibility consultant application'))
                            ->columnSpan('full')
                            ->required()
                            ->activeUrl(),
                        TextInput::make('cc_application')
                            ->label(__('Community connector application'))
                            ->columnSpan('full')
                            ->required()
                            ->activeUrl(),
                    ]),
            ]);
    }
}
