<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use Filament\Resources\Pages\EditRecord;

class EditQuiz extends EditRecord
{
    protected static string $resource = QuizResource::class;

    protected function getActions(): array
    {
        return [];
    }
}
