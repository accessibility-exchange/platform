<?php

namespace App\Filament\Resources\Libraries\Pages;

use App\Filament\Resources\Libraries\LibraryResource;
use App\Models\Library;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLibrary extends EditRecord
{
    protected static string $resource = LibraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ViewAction::make()->url(fn (Library $record): string => localized_route('libraries.show', $record)),
        ];
    }
}
