<?php

namespace App\Filament\Resources\ResourceCollectionResource\Pages;

use App\Filament\Resources\ResourceCollectionResource;
use App\Models\ResourceCollection;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResourceCollection extends EditRecord
{
    protected static string $resource = ResourceCollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ViewAction::make()->url(fn (ResourceCollection $record): string => localized_route('resource-collections.show', $record)),
        ];
    }
}
