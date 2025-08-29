<?php

namespace App\Filament\Resources\ResourceCollections\Pages;

use App\Filament\Resources\ResourceCollections\ResourceCollectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResourceCollections extends ListRecords
{
    protected static string $resource = ResourceCollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
