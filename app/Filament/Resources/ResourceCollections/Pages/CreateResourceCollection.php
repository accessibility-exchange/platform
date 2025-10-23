<?php

namespace App\Filament\Resources\ResourceCollections\Pages;

use App\Filament\Resources\ResourceCollections\ResourceCollectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResourceCollection extends CreateRecord
{
    protected static string $resource = ResourceCollectionResource::class;
}
