<?php

namespace App\Filament\Resources\Videos\Pages;

use App\Filament\Resources\Videos\VideoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVideos extends ListRecords
{
    protected static string $resource = VideoResource::class;

    protected static ?string $title = 'Videos';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
