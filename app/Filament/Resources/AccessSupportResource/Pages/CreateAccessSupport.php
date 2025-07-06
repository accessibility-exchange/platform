<?php

namespace App\Filament\Resources\AccessSupportResource\Pages;

use App\Filament\Resources\AccessSupportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAccessSupport extends CreateRecord
{
    protected static string $resource = AccessSupportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false),
        ];
    }
}
