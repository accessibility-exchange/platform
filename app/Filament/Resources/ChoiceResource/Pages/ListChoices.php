<?php

namespace App\Filament\Resources\ChoiceResource\Pages;

use App\Filament\Resources\ChoiceResource;
use Filament\Resources\Pages\ListRecords;

class ListChoices extends ListRecords
{
    protected static string $resource = ChoiceResource::class;

    protected function getActions(): array
    {
        return [];
    }
}
