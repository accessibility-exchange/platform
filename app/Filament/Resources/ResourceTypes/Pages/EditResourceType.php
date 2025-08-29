<?php

namespace App\Filament\Resources\ResourceTypes\Pages;

use App\Filament\Resources\ResourceTypes\ResourceTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResourceType extends EditRecord
{
    protected static string $resource = ResourceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
