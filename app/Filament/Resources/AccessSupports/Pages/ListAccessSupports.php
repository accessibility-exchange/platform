<?php

namespace App\Filament\Resources\AccessSupports\Pages;

use App\Filament\Resources\AccessSupports\AccessSupportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAccessSupports extends ListRecords
{
    protected static string $resource = AccessSupportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
