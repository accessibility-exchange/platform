<?php

namespace App\Filament\Resources\ResourceCollectionResource\Pages;

use App\Filament\Resources\ResourceCollectionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResourceCollections extends ListRecords
{
    protected static string $resource = ResourceCollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
