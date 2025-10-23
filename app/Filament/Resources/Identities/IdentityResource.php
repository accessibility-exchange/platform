<?php

namespace App\Filament\Resources\Identities;

use App\Enums\IdentityCluster;
use App\Filament\Resources\Identities\Pages\CreateIdentity;
use App\Filament\Resources\Identities\Pages\ListIdentities;
use App\Models\Identity;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IdentityResource extends Resource
{
    protected static ?string $model = Identity::class;

    protected static ?int $navigationSort = 4;

    protected static string|\UnitEnum|null $navigationGroup = 'Metadata';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name.en')
                    ->label(__('Name (English)'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('name.fr')
                    ->label(__('Name (French)'))
                    ->required()
                    ->maxLength(255),
                Textarea::make('description.en')
                    ->label(__('Description (English)'))
                    ->columnSpan(2),
                Textarea::make('description.fr')
                    ->label(__('Description (French)'))
                    ->columnSpan(2),
                CheckboxList::make('clusters')
                    ->options(self::getClusters())
                    ->label(__('Cluster')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable(),
                TextColumn::make('clusters')
                    ->badge()
                    ->getStateUsing(fn (Identity $record): array => $record->clusters ? Arr::map($record->clusters, fn ($cluster) => IdentityCluster::labels()[$cluster]) : [])
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Date added'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(
                [
                    SelectFilter::make('clusters')
                        ->multiple()
                        ->query(fn (Builder $query, array $data): Builder => $query->whereJsonContains('clusters', $data['values']))
                        ->options(self::getClusters()),
                ],
                layout: FiltersLayout::AboveContent
            )
            ->recordActions([])
            ->toolbarActions([])
            ->defaultSort('clusters')
            ->paginated([10, 25, 50, 'all']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIdentities::route('/'),
            'create' => CreateIdentity::route('/create'),
        ];
    }

    public static function getClusters(): array
    {
        $clusters = [];
        foreach (array_column(IdentityCluster::cases(), 'value') as $key) {
            $clusters[$key] = IdentityCluster::labels()[$key];
        }

        return $clusters;
    }
}
