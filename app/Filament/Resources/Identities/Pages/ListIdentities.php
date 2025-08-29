<?php

namespace App\Filament\Resources\Identities\Pages;

use App\Filament\Resources\Identities\IdentityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListIdentities extends ListRecords
{
    protected static string $resource = IdentityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->withoutGlobalScopes();
    }
}
