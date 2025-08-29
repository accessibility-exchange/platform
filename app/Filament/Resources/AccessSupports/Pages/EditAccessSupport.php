<?php

namespace App\Filament\Resources\AccessSupports\Pages;

use App\Filament\Resources\AccessSupports\AccessSupportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAccessSupport extends EditRecord
{
    protected static string $resource = AccessSupportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
