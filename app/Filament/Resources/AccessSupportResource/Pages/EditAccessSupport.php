<?php

namespace App\Filament\Resources\AccessSupportResource\Pages;

use App\Filament\Resources\AccessSupportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccessSupport extends EditRecord
{
    protected static string $resource = AccessSupportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
