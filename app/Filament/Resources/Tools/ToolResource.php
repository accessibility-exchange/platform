<?php

namespace App\Filament\Resources\Tools;

use App\Filament\Resources\Tools\Pages\CreateTool;
use App\Filament\Resources\Tools\Pages\EditTool;
use App\Filament\Resources\Tools\Pages\ListTools;
use App\Filament\Resources\Tools\RelationManagers\DocumentsRelationManager;
use App\Models\Tool;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ToolResource extends Resource
{
    protected static ?string $model = Tool::class;

    protected static ?int $navigationSort = 8;

    protected static string|\UnitEnum|null $navigationGroup = 'Pages, resources and training';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title.en')
                    ->label(__('Tool title').' ('.get_language_exonym('en').')')
                    ->requiredWithout('title.fr'),
                TextInput::make('title.fr')
                    ->label(__('Tool title').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('title.en'),
                MarkdownEditor::make('description.en')
                    ->toolbarButtons(['bold', 'italic', 'edit', 'preview'])
                    ->label(__('Description').' ('.get_language_exonym('en').')'),
                MarkdownEditor::make('description.fr')
                    ->toolbarButtons(['bold', 'italic', 'edit', 'preview'])
                    ->label(__('Description').' ('.get_language_exonym('fr').')'),
                Section::make('Page Content')
                    ->columnSpanFull()
                    ->schema([
                        MarkdownEditor::make('content.en')
                            ->disableToolbarButtons(['attachFiles'])
                            ->label(__('Content').' ('.get_language_exonym('en').')')
                            ->columnSpan(2),
                        MarkdownEditor::make('content.fr')
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
                TextColumn::make('title'),
                TextColumn::make('documents_count')
                    ->label(__('Documents'))
                    ->counts('documents'),
                TextColumn::make('created_at')
                    ->label(__('Date added'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Date modified'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make()
                    ->tooltip(fn (Tool $record): string => __('Edit :title', ['title' => $record->title])),
                ViewAction::make()->url(fn (Tool $record): string => localized_route('tools.show', $record))
                    ->tooltip(fn (Tool $record): string => __('View :title', ['title' => $record->title])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTools::route('/'),
            'create' => CreateTool::route('/create'),
            'edit' => EditTool::route('/{record}/edit'),
        ];
    }
}
