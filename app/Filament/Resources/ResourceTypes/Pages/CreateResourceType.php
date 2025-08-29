<?php

namespace App\Filament\Resources\ResourceTypes\Pages;

use App\Filament\Resources\ResourceTypes\ResourceTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResourceType extends CreateRecord
{
    protected static string $resource = ResourceTypeResource::class;
}
