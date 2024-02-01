<?php

namespace App\Filament\Resources\ImpactResource\Pages;

use App\Filament\Resources\ImpactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImpacts extends ListRecords
{
    protected static string $resource = ImpactResource::class;

    protected static ?string $title = 'Areas of Accessibility Planning';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
