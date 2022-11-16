<?php

namespace App\Filament\Resources\InterpretationResource\Pages;

use App\Filament\Resources\InterpretationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInterpretation extends EditRecord
{
    protected static string $resource = InterpretationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
