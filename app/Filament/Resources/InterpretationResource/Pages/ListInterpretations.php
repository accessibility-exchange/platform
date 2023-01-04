<?php

namespace App\Filament\Resources\InterpretationResource\Pages;

use App\Filament\Resources\InterpretationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterpretations extends ListRecords
{
    protected static string $resource = InterpretationResource::class;

    protected static ?string $title = 'Sign language interpretations';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
