<?php

namespace App\Filament\Resources\ResourceCollectionResource\Pages;

use App\Filament\Resources\ResourceCollectionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResourceCollection extends EditRecord
{
    protected static string $resource = ResourceCollectionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
