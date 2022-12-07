<?php

namespace App\Filament\Resources;

use App\Enums\IdentityCluster;
use App\Filament\Resources\IdentityResource\Pages;
use App\Models\Identity;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IdentityResource extends Resource
{
    protected static ?string $model = Identity::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name.en')
                    ->label(__('Name (English)'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name.fr')
                    ->label(__('Name (French)'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description.en')
                    ->label(__('Description (English)'))
                    ->columnSpan(2),
                Forms\Components\Textarea::make('description.fr')
                    ->label(__('Description (French)'))
                    ->columnSpan(2),
                Forms\Components\CheckboxList::make('clusters')
                    ->options(self::getClusters())
                    ->label(__('Cluster')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
                Tables\Columns\TagsColumn::make('clusters')
                    ->getStateUsing(fn (Identity $record): array => $record->clusters ? Arr::map($record->clusters, fn ($cluster) => IdentityCluster::labels()[$cluster]) : [])
                    ->sortable(),
            ])
            ->filters(
                [
                    SelectFilter::make('clusters')
                        ->multiple()
                        ->query(fn (Builder $query, array $data): Builder => $query->whereJsonContains('clusters', $data['values']))
                        ->options(self::getClusters()),
                ],
                layout: Layout::AboveContent
            )
            ->actions([])
            ->bulkActions([])
            ->defaultSort('clusters');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIdentities::route('/'),
            'create' => Pages\CreateIdentity::route('/create'),
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
