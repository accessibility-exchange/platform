<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageGeneralSettings extends SettingsPage
{
    protected static ?string $navigationLabel = 'Website settings';

    protected static ?int $navigationSort = 2;

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
                        Fieldset::make(__('Support email'))
                            ->schema([
                                TextInput::make('email.en')
                                    ->label(get_language_exonym('en'))
                                    ->required()
                                    ->email(),
                                TextInput::make('email.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->email(),
                            ]),
                        Fieldset::make(__('Support phone'))
                            ->schema([
                                TextInput::make('phone.en')
                                    ->label(get_language_exonym('en'))
                                    ->required(),
                                TextInput::make('phone.fr')
                                    ->label(get_language_exonym('fr')),
                            ]),
                        Fieldset::make(__('Privacy email'))
                            ->schema([
                                TextInput::make('email_privacy.en')
                                    ->label(get_language_exonym('en'))
                                    ->required()
                                    ->email(),
                                TextInput::make('email_privacy.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->email(),
                            ]),
                        Fieldset::make(__('Mailing address'))
                            ->schema([
                                Textarea::make('address.en')
                                    ->label(get_language_exonym('en'))
                                    ->rows(3)
                                    ->autosize()
                                    ->required(),
                                Textarea::make('address.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->rows(3)
                                    ->autosize(),
                            ]),
                    ])
                    ->columns(2),
                Section::make(__('Social media'))
                    ->schema([
                        Fieldset::make(__('Facebook page'))
                            ->schema([
                                TextInput::make('facebook.en')
                                    ->label(get_language_exonym('en'))
                                    ->required()
                                    ->activeUrl(),
                                TextInput::make('facebook.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->activeUrl(),
                            ]),
                        Fieldset::make(__('LinkedIn page'))
                            ->schema([
                                TextInput::make('linkedin.en')
                                    ->label(get_language_exonym('en'))
                                    ->required()
                                    ->activeUrl(),
                                TextInput::make('linkedin.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->activeUrl(),
                            ]),
                        Fieldset::make(__('Twitter page'))
                            ->schema([
                                TextInput::make('twitter.en')
                                    ->label(get_language_exonym('en'))
                                    ->required()
                                    ->activeUrl(),
                                TextInput::make('twitter.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->activeUrl(),
                            ]),
                        Fieldset::make(__('YouTube page'))
                            ->schema([
                                TextInput::make('youtube.en')
                                    ->label(get_language_exonym('en'))
                                    ->required()
                                    ->activeUrl(),
                                TextInput::make('youtube.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->activeUrl(),
                            ]),
                    ])
                    ->columns(2),
                Section::make(__('Registration'))
                    ->schema([
                        Fieldset::make(__('Individual orientation'))
                            ->schema([
                                TextInput::make('individual_orientation.en')
                                    ->label(get_language_exonym('en'))
                                    ->required()
                                    ->activeUrl(),
                                TextInput::make('individual_orientation.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->activeUrl(),
                            ]),
                        Fieldset::make(__('Community organization orientation'))
                            ->schema([
                                TextInput::make('org_orientation.en')
                                    ->label(get_language_exonym('en'))
                                    ->required()
                                    ->activeUrl(),
                                TextInput::make('org_orientation.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->activeUrl(),
                            ]),
                        Fieldset::make(__('Federally regulated organization orientation'))
                            ->schema([
                                TextInput::make('fro_orientation.en')
                                    ->label(get_language_exonym('en'))
                                    ->required()
                                    ->activeUrl(),
                                TextInput::make('fro_orientation.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->activeUrl(),
                            ]),
                        Fieldset::make(__('Accessibility Consultant and Community Connector application'))
                            ->schema([
                                TextInput::make('ac_cc_application.en')
                                    ->label(get_language_exonym('en'))
                                    ->required()
                                    ->activeUrl(),
                                TextInput::make('ac_cc_application.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->activeUrl(),
                            ]),
                    ])
                    ->columns(2),
            ]);
    }
}
