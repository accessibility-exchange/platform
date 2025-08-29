<?php

namespace App\Filament\Resources\Interpretations\Pages;

use App\Filament\Resources\Interpretations\InterpretationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInterpretation extends EditRecord
{
    protected static string $resource = InterpretationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
