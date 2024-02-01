<?php

namespace App\Filament\Resources\ImpactResource\Pages;

use App\Filament\Resources\ImpactResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImpact extends EditRecord
{
    protected static string $resource = ImpactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
