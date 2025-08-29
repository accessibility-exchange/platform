<?php

namespace App\Filament\Resources\Resources\Pages;

use App\Filament\Resources\Resources\ResourceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResources extends ListRecords
{
    protected static string $resource = ResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
