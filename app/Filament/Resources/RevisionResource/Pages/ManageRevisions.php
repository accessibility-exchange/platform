<?php

namespace App\Filament\Resources\RevisionResource\Pages;

use App\Filament\Resources\RevisionResource;
use Filament\Resources\Pages\ManageRecords;

class ManageRevisions extends ManageRecords
{
    protected static string $resource = RevisionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
