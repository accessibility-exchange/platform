<?php

namespace App\Filament\Resources\Libraries;

use App\Filament\Resources\Libraries\Pages\CreateLibrary;
use App\Filament\Resources\Libraries\Pages\EditLibrary;
use App\Filament\Resources\Libraries\Pages\ListLibraries;
use App\Filament\Resources\Libraries\RelationManagers\ResourceCollectionsRelationManager;
use App\Models\Library;
use Closure;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LibraryResource extends Resource
{
    protected static ?string $model = Library::class;

    protected static ?int $navigationSort = 7;

    protected static string|\UnitEnum|null $navigationGroup = 'Pages, resources and training';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title.en')
                    ->label(__('Library title').' ('.get_language_exonym('en').')')
                    ->requiredWithout('title.fr'),
                TextInput::make('title.fr')
                    ->label(__('Library title').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('title.en'),
                Toggle::make('featured')
                    ->label(__('Featured'))
                    ->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                            if (Library::where('featured', true)->count() === 4 && $value == true) {
                                $fail(__('Only four libraries may be featured.'));
                            }
                        },
                    ]),
                MarkdownEditor::make('description.en')
                    ->toolbarButtons([
                        ['bold', 'italic'],
                        ['undo', 'redo'],
                    ])
                    ->label(__('Description').' ('.get_language_exonym('en').')')
                    ->columnSpan(2),
                MarkdownEditor::make('description.fr')
                    ->toolbarButtons([
                        ['bold', 'italic'],
                        ['undo', 'redo'],
                    ])
                    ->label(__('Description').' ('.get_language_exonym('fr').')')
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->sortable(),
                TextColumn::make('featured')
                    ->badge()
                    ->color(fn (string $state): string => $state ? 'success' : false)
                    ->formatStateUsing(fn (bool $state): string => $state ? __('Yes') : '')
                    ->icon(fn (string $state): string => $state ? 'heroicon-s-star' : false),
                TextColumn::make('order')
                    ->label(__('Order'))
                    ->sortable(),
                TextColumn::make('resource_collections_count')
                    ->label(__('Resource Collections'))
                    ->counts('resourceCollections'),
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
            ->filters([])
            ->recordActions([
                EditAction::make()->tooltip(fn (Library $record): string => __('Edit :title', ['title' => $record->title])),
                ViewAction::make()->url(fn (Library $record): string => localized_route('libraries.show', $record))->tooltip(fn (Library $record): string => __('Edit :title', ['title' => $record->title])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->paginated([10, 25, 50, 'all']);
    }

    public static function getRelations(): array
    {
        return [
            ResourceCollectionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLibraries::route('/'),
            'create' => CreateLibrary::route('/create'),
            'edit' => EditLibrary::route('/{record}/edit'),
        ];
    }
}
