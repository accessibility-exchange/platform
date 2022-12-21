<?php

namespace App\Filament\Resources\ChoiceResource\Pages;

use App\Filament\Resources\ChoiceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChoice extends EditRecord
{
    protected static string $resource = ChoiceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
