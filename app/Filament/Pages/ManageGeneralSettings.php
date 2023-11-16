<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Spatie\Translatable\HasTranslations;

class ManageGeneralSettings extends SettingsPage
{
    use HasTranslations;

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
                        Fieldset::make(__('Accessibility consultant application'))
                            ->schema([
                                TextInput::make('ac_application.en')
                                    ->label(get_language_exonym('en'))
                                    ->required()
                                    ->activeUrl(),
                                TextInput::make('ac_application.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->activeUrl(),
                            ]),
                        Fieldset::make(__('Community connector application'))
                            ->schema([
                                TextInput::make('cc_application.en')
                                    ->label(get_language_exonym('en'))
                                    ->required()
                                    ->activeUrl(),
                                TextInput::make('cc_application.fr')
                                    ->label(get_language_exonym('fr'))
                                    ->activeUrl(),
                            ]),
                    ])
                    ->columns(2),
            ]);
    }
}
