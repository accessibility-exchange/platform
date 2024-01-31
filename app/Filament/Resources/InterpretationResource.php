<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InterpretationResource\Pages;
use App\Models\Interpretation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InterpretationResource extends Resource
{
    protected static ?string $model = Interpretation::class;

    protected static ?string $navigationIcon = 'tae-sign-language';

    protected static ?string $navigationLabel = 'Sign Language Interpretations';

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
                    ->disabled()
                    ->maxLength(255),
                Forms\Components\TextInput::make('namespace')
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('namespace'),
                Tables\Columns\TextColumn::make('asl')
                    ->badge()
                    ->getStateUsing(fn (Interpretation $record): string => $record->getTranslation('video', 'asl', false) !== '' ? __('Yes') : __('No'))
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
                    ->label('ASL Video'),
                Tables\Columns\TextColumn::make('lsq')
                    ->badge()
                    ->getStateUsing(fn (Interpretation $record): string => $record->getTranslation('video', 'lsq', false) !== '' ? __('Yes') : __('No'))
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
                    ->label('LSQ Video'),
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
                // TODO
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->tooltip(fn (Interpretation $record): string => "Edit {$record->name}"),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->paginated([10, 25, 50, 'all']);
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
