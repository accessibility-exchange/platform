<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-s-rectangle-stack';

    protected static ?int $navigationSort = 5;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title.en')
                    ->label(__('Page title').' ('.get_language_exonym('en').')')
                    ->requiredWithout('title.fr'),
                Forms\Components\TextInput::make('title.fr')
                    ->label(__('Page title').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('title.en'),
                Forms\Components\Section::make('Page Content')
                    ->description(__('The following values will be expanded in the output to their full URL or email address: ":home", ":tos", ":privacy_policy", ":email", and ":email_privacy". You may wrap these values in "<>" to display the expanded output itself.'))
                    ->schema([
                        Forms\Components\MarkdownEditor::make('content.en')
                            ->disableToolbarButtons(['attachFiles'])
                            ->label(__('Content').' ('.get_language_exonym('en').')')
                            ->columnSpan(2),
                        Forms\Components\MarkdownEditor::make('content.fr')
                            ->disableToolbarButtons(['attachFiles'])
                            ->label(__('Content').' ('.get_language_exonym('fr').')')
                            ->columnSpan(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date added'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Date modified'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()->url(fn (Page $record): string => localized_route('about.page', $record)),
            ])
            ->bulkActions([])
            ->paginated([10, 25, 50, 'all']);
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
