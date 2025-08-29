<?php

namespace App\Filament\Resources\Tools\Pages;

use App\Filament\Resources\Tools\ToolResource;
use App\Models\Tool;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTool extends EditRecord
{
    protected static string $resource = ToolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ViewAction::make()->url(fn (Tool $record): string => localized_route('tools.show', $record)),
        ];
    }
}
