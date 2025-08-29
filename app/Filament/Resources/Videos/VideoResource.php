<?php

namespace App\Filament\Resources\Videos;

use App\Filament\Resources\Videos\Pages\CreateVideo;
use App\Filament\Resources\Videos\Pages\EditVideo;
use App\Filament\Resources\Videos\Pages\ListVideos;
use App\Models\Video;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationLabel = 'Videos';

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
                TextInput::make('video.en')
                    ->label('English Video')
                    ->url()
                    ->maxLength(255),
                TextInput::make('video.fr')
                    ->label('French Video')
                    ->url()
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
                TextColumn::make('en')
                    ->badge()
                    ->getStateUsing(fn (Video $record): string => $record->getTranslation('video', 'en', false) !== '' ? __('Yes') : __('No'))
                    ->color(fn (string $state): string => $state === __('Yes') ? 'success' : 'danger')
                    ->icon(fn (string $state): string => $state ? 'heroicon-o-check' : 'heroicon-o-x-mark')
                    ->label('English'),
                TextColumn::make('fr')
                    ->badge()
                    ->getStateUsing(fn (Video $record): string => $record->getTranslation('video', 'fr', false) !== '' ? __('Yes') : __('No'))
                    ->color(fn (string $state): string => $state === __('Yes') ? 'success' : 'danger')
                    ->icon(fn (string $state): string => $state ? 'heroicon-o-check' : 'heroicon-o-x-mark')
                    ->label('French'),
                TextColumn::make('asl')
                    ->badge()
                    ->getStateUsing(fn (Video $record): string => $record->getTranslation('video', 'asl', false) !== '' ? __('Yes') : __('No'))
                    ->colors([
                        'success' => static fn ($state): bool => $state === __('Yes'),
                        'danger' => static fn ($state): bool => $state === __('No'),
                    ])
                    ->icon(static function ($state): string {
                        if ($state === __('Yes')) {
                            return 'heroicon-o-check';
                        }

                        return 'heroicon-o-x-mark';
                    })
                    ->label('ASL'),
                TextColumn::make('lsq')
                    ->badge()
                    ->getStateUsing(fn (Video $record): string => $record->getTranslation('video', 'lsq', false) !== '' ? __('Yes') : __('No'))
                    ->colors([
                        'success' => static fn ($state): bool => $state === __('Yes'),
                        'danger' => static fn ($state): bool => $state === __('No'),
                    ])
                    ->icon(static function ($state): string {
                        if ($state === __('Yes')) {
                            return 'heroicon-o-check';
                        }

                        return 'heroicon-o-x-mark';
                    })
                    ->label('LSQ'),
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
                    ->tooltip(fn (Video $record): string => __('Edit :name', ['name' => $record->name])),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->paginated([10, 25, 50, 'all']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVideos::route('/'),
            'create' => CreateVideo::route('/create'),
            'edit' => EditVideo::route('/{record}/edit'),
        ];
    }
}
