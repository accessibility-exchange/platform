<?php

namespace App\Filament\Resources\ImpactResource\Pages;

use App\Filament\Resources\ImpactResource;
use Filament\Resources\Pages\CreateRecord;

class CreateImpact extends CreateRecord
{
    protected static string $resource = ImpactResource::class;

    protected static ?string $title = 'Create Area of Accessibility Planning';
}
