<?php

namespace App\Filament\Resources\InterpretationResource\Pages;

use App\Filament\Resources\InterpretationResource;
use Filament\Forms;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInterpretation extends EditRecord
{
    protected static string $resource = InterpretationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
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
                ->columnSpan(2)
                ->disabled(),
            Forms\Components\TextInput::make('video.asl')
                ->label('ASL Video')
                ->url()
                ->maxLength(255),
            Forms\Components\TextInput::make('video.lsq')
                ->label('LSQ Video')
                ->url()
                ->maxLength(255),
        ];
    }
}
