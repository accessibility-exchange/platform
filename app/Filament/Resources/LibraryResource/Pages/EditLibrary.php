<?php

namespace App\Filament\Resources\LibraryResource\Pages;

use App\Filament\Resources\LibraryResource;
use App\Models\Library;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLibrary extends EditRecord
{
    protected static string $resource = LibraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ViewAction::make()->url(fn (Library $record): string => localized_route('libraries.show', $record)),
        ];
    }
}
