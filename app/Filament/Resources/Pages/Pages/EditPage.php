<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use App\Models\Page;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title.en')
                    ->label(__('Page title').' ('.get_language_exonym('en').')')
                    ->disabled(),
                TextInput::make('title.fr')
                    ->label(__('Page title').' ('.get_language_exonym('fr').')')
                    ->disabled(),
                Section::make('Page Content')
                    ->columnSpanFull()
                    ->description(__('The following values will be expanded in the output to their full URL or email address: ":home", ":tos", ":privacy_policy", ":email", and ":email_privacy". You may wrap these values in "<>" to display the expanded output itself.'))
                    ->schema([
                        MarkdownEditor::make('content.en')
                            ->toolbarButtons([
                                ['bold', 'italic', 'strike', 'link'],
                                ['heading'],
                                ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                ['table', 'attachFiles'],
                                ['undo', 'redo'],
                            ])
                            ->label(__('Content').' ('.get_language_exonym('en').')')
                            ->columnSpan(2),
                        MarkdownEditor::make('content.fr')
                            ->toolbarButtons([
                                ['bold', 'italic', 'strike', 'link'],
                                ['heading'],
                                ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                ['table', 'attachFiles'],
                                ['undo', 'redo'],
                            ])
                            ->label(__('Content').' ('.get_language_exonym('fr').')')
                            ->columnSpan(2),
                    ]),
            ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['title']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->url(fn (Page $record): string => localized_route('about.page', $record)),
        ];
    }
}
