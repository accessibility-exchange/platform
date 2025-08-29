<?php

namespace App\Filament\Resources\Revisions\Pages;

use App\Filament\Resources\Revisions\RevisionResource;
use Filament\Resources\Pages\ManageRecords;

class ManageRevisions extends ManageRecords
{
    protected static string $resource = RevisionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
