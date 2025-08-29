<?php

namespace App\Filament\Resources\Interpretations\Pages;

use App\Filament\Resources\Interpretations\InterpretationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInterpretations extends ListRecords
{
    protected static string $resource = InterpretationResource::class;

    protected static ?string $title = 'Sign language interpretations';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
