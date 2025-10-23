<?php

namespace App\Filament\Resources\Impacts\Pages;

use App\Filament\Resources\Impacts\ImpactResource;
use Filament\Resources\Pages\CreateRecord;

class CreateImpact extends CreateRecord
{
    protected static string $resource = ImpactResource::class;

    protected static ?string $title = 'Create Area of Accessibility Planning';
}
