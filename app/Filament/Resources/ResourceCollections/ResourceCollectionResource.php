<?php

namespace App\Filament\Resources\ResourceCollections;

use App\Filament\Resources\ResourceCollections\Pages\CreateResourceCollection;
use App\Filament\Resources\ResourceCollections\Pages\EditResourceCollection;
use App\Filament\Resources\ResourceCollections\Pages\ListResourceCollections;
use App\Filament\Resources\ResourceCollections\RelationManagers\ResourcesRelationManager;
use App\Models\ResourceCollection;
use Closure;
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

class ResourceCollectionResource extends Resource
{
    protected static ?string $model = ResourceCollection::class;

    protected static ?int $navigationSort = 7;

    protected static string|\UnitEnum|null $navigationGroup = 'Pages, resources and training';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title.en')
                    ->label(__('Resource collection title').' ('.get_language_exonym('en').')')
                    ->requiredWithout('title.fr'),
                TextInput::make('title.fr')
                    ->label(__('Resource collection title').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('title.en'),
                Toggle::make('featured')
                    ->label(__('Featured'))
                    ->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                            if (ResourceCollection::where('featured', true)->count() === 4 && $value == true) {
                                $fail(__('Only four resource collections may be featured.'));
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
                TextColumn::make('resources_count')
                    ->label(__('Resources'))
                    ->counts('resources'),
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
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->tooltip(fn (ResourceCollection $record): string => __('Edit :title', ['title' => $record->title])),
                ViewAction::make()->url(fn (ResourceCollection $record): string => localized_route('resource-collections.show', $record))->tooltip(fn (ResourceCollection $record): string => __('View :title', ['title' => $record->title])),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->reorderable('order')
            ->paginated([10, 25, 50, 'all']);
    }

    public static function getRelations(): array
    {
        return [
            ResourcesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResourceCollections::route('/'),
            'create' => CreateResourceCollection::route('/create'),
            'edit' => EditResourceCollection::route('/{record}/edit'),
        ];
    }
}
