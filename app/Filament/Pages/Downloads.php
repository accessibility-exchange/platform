<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Activity;

class Downloads extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'Analytics';

    protected static string $view = 'filament.pages.downloads';

    public function table(Table $table): Table
    {
        return $table
            ->query(Activity::where('event', 'downloaded'))
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('Downloaded')),
                TextColumn::make('subject.document.name')
                    ->label(__('Document')),
                TextColumn::make('subject.date')
                    ->label(__('Revision Date'))
                    ->formatStateUsing(fn (Carbon $state) => $state->format('Y-m-d')),
                TextColumn::make('properties.language')
                    ->label(__('Language'))
                    ->formatStateUsing(fn (string $state) => get_language_exonym($state)),
                TextColumn::make('properties.email')
                    ->label(__('Email'))
                    ->default(__('N/A')),
                TextColumn::make('causer_id')
                    ->label(__('Registered'))
                    ->default(fn ($record) => $record->causer_id !== null)
                    ->badge()
                    ->color(fn ($state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? __('Yes') : __('No')),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // ...
            ])
            ->emptyStateHeading(__('No downloads yet'));
    }
}
