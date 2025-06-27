<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResourceCollectionResource\Pages;
use App\Models\ResourceCollection;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ResourceCollectionResource extends Resource
{
    protected static ?string $model = ResourceCollection::class;

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationGroup = 'Pages, resources and training';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title.en')
                    ->label(__('Resource collection title').' ('.get_language_exonym('en').')')
                    ->requiredWithout('title.fr'),
                Forms\Components\TextInput::make('title.fr')
                    ->label(__('Resource collection title').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('title.en'),
                Forms\Components\Toggle::make('featured')
                    ->label(__('Featured'))
                    ->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                            if (ResourceCollection::where('featured', true)->count() === 4 && $value == true) {
                                $fail(__('Only four resource collections may be featured.'));
                            }
                        },
                    ]),
                Forms\Components\MarkdownEditor::make('description.en')
                    ->toolbarButtons(['bold', 'italic', 'edit', 'preview'])
                    ->label(__('Description').' ('.get_language_exonym('en').')')
                    ->columnSpan(2),
                Forms\Components\MarkdownEditor::make('description.fr')
                    ->toolbarButtons(['bold', 'italic', 'edit', 'preview'])
                    ->label(__('Description').' ('.get_language_exonym('fr').')')
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable(),
                Tables\Columns\TextColumn::make('featured')
                    ->badge()
                    ->color(fn (string $state): string => $state ? 'success' : false)
                    ->formatStateUsing(fn (bool $state): string => $state ? __('Yes') : '')
                    ->icon(fn (string $state): string => $state ? 'heroicon-s-star' : false),
                Tables\Columns\TextColumn::make('order')
                    ->label(__('Order'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('resources_count')
                    ->label(__('Resources'))
                    ->counts('resources'),
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
                Tables\Actions\ViewAction::make()->url(fn (ResourceCollection $record): string => localized_route('resource-collections.show', $record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->reorderable('order')
            ->paginated([10, 25, 50, 'all']);
    }

    public static function getRelations(): array
    {
        return [
            ResourceCollectionResource\RelationManagers\ResourcesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResourceCollections::route('/'),
            'create' => Pages\CreateResourceCollection::route('/create'),
            'edit' => Pages\EditResourceCollection::route('/{record}/edit'),
        ];
    }
}
