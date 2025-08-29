<?php

namespace App\Filament\Resources\ResourceCollections\Pages;

use App\Filament\Resources\ResourceCollections\ResourceCollectionResource;
use App\Models\ResourceCollection;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditResourceCollection extends EditRecord
{
    protected static string $resource = ResourceCollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ViewAction::make()->url(fn (ResourceCollection $record): string => localized_route('resource-collections.show', $record)),
        ];
    }
}
