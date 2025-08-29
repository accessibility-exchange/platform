<?php

namespace App\Filament\Resources\Languages\Pages;

use App\Filament\Resources\Languages\LanguageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLanguages extends ListRecords
{
    protected static string $resource = LanguageResource::class;

    protected static ?string $title = 'User Languages';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
