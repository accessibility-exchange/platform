<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ManageGeneralSettings extends SettingsPage
{
    protected static ?string $navigationLabel = 'Website settings';

    protected static ?int $navigationSort = 2;

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $slug = 'settings';

    protected static string $settings = GeneralSettings::class;

    public static ?string $title = 'Website settings';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Contact'))
                    ->columnSpanFull()
                    ->schema([
                        Fieldset::make(__('Support email'))
                            ->columnSpanFull()
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
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('phone.en')
                                    ->label(get_language_exonym('en'))
                                    ->required(),
                                TextInput::make('phone.fr')
                                    ->label(get_language_exonym('fr')),
                            ]),
                        Fieldset::make(__('VRS'))
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('vrs.en')
                                    ->label(get_language_exonym('en'))
                                    ->required()
                                    ->activeUrl(),
                                TextInput::make('vrs.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->activeUrl(),
                            ]),
                        Fieldset::make(__('Privacy email'))
                            ->columnSpanFull()
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
                            ->columnSpanFull()
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
                    ->columnSpanFull()
                    ->schema([
                        Fieldset::make(__('Facebook page'))
                            ->columnSpanFull()
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
                            ->columnSpanFull()
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
                            ->columnSpanFull()
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
                            ->columnSpanFull()
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
                    ->columnSpanFull()
                    ->schema([
                        Fieldset::make(__('Individual orientation'))
                            ->columnSpanFull()
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
                            ->columnSpanFull()
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
                            ->columnSpanFull()
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
                            ->columnSpanFull()
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
