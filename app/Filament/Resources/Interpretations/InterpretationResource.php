<?php

namespace App\Filament\Resources\Interpretations;

use App\Filament\Resources\Interpretations\Pages\CreateInterpretation;
use App\Filament\Resources\Interpretations\Pages\EditInterpretation;
use App\Filament\Resources\Interpretations\Pages\ListInterpretations;
use App\Models\Interpretation;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InterpretationResource extends Resource
{
    protected static ?string $model = Interpretation::class;

    protected static ?string $navigationLabel = 'Sign language interpretations';

    protected static ?int $navigationSort = 1;

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                TextInput::make('route')
                    ->required()
                    ->disabled()
                    ->maxLength(255),
                TextInput::make('namespace')
                    ->maxLength(255),
                TextInput::make('video.asl')
                    ->label('ASL Video')
                    ->url()
                    ->maxLength(255),
                TextInput::make('video.lsq')
                    ->label('LSQ Video')
                    ->url()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('namespace'),
                TextColumn::make('asl')
                    ->badge()
                    ->getStateUsing(fn (Interpretation $record): string => $record->getTranslation('video', 'asl', false) !== '' ? __('Yes') : __('No'))
                    ->color(fn (string $state): string => $state === __('Yes') ? 'success' : 'danger')
                    ->icon(fn (string $state): string => $state ? 'heroicon-o-check' : 'heroicon-o-x-mark')
                    ->label('ASL Video'),
                TextColumn::make('lsq')
                    ->badge()
                    ->getStateUsing(fn (Interpretation $record): string => $record->getTranslation('video', 'lsq', false) !== '' ? __('Yes') : __('No'))
                    ->color(fn (string $state): string => $state === __('Yes') ? 'success' : 'danger')
                    ->icon(fn (string $state): string => $state ? 'heroicon-o-check' : 'heroicon-o-x-mark')
                    ->label('LSQ Video'),
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
                // TODO
            ])
            ->recordActions([
                EditAction::make()
                    ->tooltip(fn (Interpretation $record): string => __('Edit :name', ['name' => $record->name])),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->paginated([10, 25, 50, 'all']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInterpretations::route('/'),
            'create' => CreateInterpretation::route('/create'),
            'edit' => EditInterpretation::route('/{record}/edit'),
        ];
    }
}
