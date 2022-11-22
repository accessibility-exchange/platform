<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InterpretationResource\Pages;
use App\Models\Interpretation;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;

class InterpretationResource extends Resource
{
    protected static ?string $model = Interpretation::class;

    protected static ?string $navigationIcon = 'tae-sign-language';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('route')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('namespace')
                    ->maxLength(255),
                Forms\Components\Toggle::make('route_has_params')
                    ->label('Route has parameters')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('video.asl')
                    ->label('ASL Video')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('video.lsq')
                    ->label('LSQ Video')
                    ->url()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->disableClick(),
                Tables\Columns\TextColumn::make('context')
                    ->label(__('Show context'))
                    ->getStateUsing(fn (Interpretation $record): string => __('Show context').' <span class="sr-only"> '.__('for').' '.$record->name.'</span>')
                    ->html()
                    ->url(fn (Interpretation $record): string => $record->route_has_params ? route('filament.resources.interpretations.edit', $record) : localized_route($record->route).'#'.Str::slug($record->name))
                    ->openUrlInNewTab()
                    ->icon('heroicon-s-external-link')
                    ->iconPosition('after'),
                Tables\Columns\BadgeColumn::make('asl')
                    ->getStateUsing(fn (Interpretation $record): string => $record->getTranslation('video', 'asl', false) !== '' ? __('Yes') : __('No'))
                    ->colors([
                        'success' => static fn ($state): bool => $state === __('Yes'),
                        'danger' => static fn ($state): bool => $state === __('No'),
                    ])
                    ->icon(static function ($state): string {
                        if ($state === __('Yes')) {
                            return 'heroicon-o-check';
                        }

                        return 'heroicon-o-x';
                    })
                    ->label('ASL Video')
                    ->disableClick(),
                Tables\Columns\BadgeColumn::make('lsq')
                    ->getStateUsing(fn (Interpretation $record): string => $record->getTranslation('video', 'lsq', false) !== '' ? __('Yes') : __('No'))
                    ->colors([
                        'success' => static fn ($state): bool => $state === __('Yes'),
                        'danger' => static fn ($state): bool => $state === __('No'),
                    ])
                    ->icon(static function ($state): string {
                        if ($state === __('Yes')) {
                            return 'heroicon-o-check';
                        }

                        return 'heroicon-o-x';
                    })
                    ->label('LSQ Video')
                    ->disableClick(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()
                    ->disableClick(),
            ])
            ->filters([
                // TODO
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->tooltip(fn (Interpretation $record): string => "Edit {$record->name}"),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInterpretations::route('/'),
            'create' => Pages\CreateInterpretation::route('/create'),
            'edit' => Pages\EditInterpretation::route('/{record}/edit'),
        ];
    }
}
