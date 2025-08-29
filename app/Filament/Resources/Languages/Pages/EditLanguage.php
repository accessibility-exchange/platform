<?php

namespace App\Filament\Resources\Languages\Pages;

use App\Filament\Resources\Languages\LanguageResource;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditLanguage extends EditRecord
{
    protected static string $resource = LanguageResource::class;

    protected static ?string $title = 'Edit User Language';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name.en')
                    ->label(__('Name (English)'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('name.fr')
                    ->label(__('Name (French)'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->required()
                    ->maxLength(255)
                    ->dehydrated()
                    ->disabled(),
            ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['name']['asl'] = $data['name']['en'];
        $data['name']['lsq'] = $data['name']['fr'];

        return $data;
    }
}
