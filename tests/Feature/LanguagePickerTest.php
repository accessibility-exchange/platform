<?php

namespace Tests\Feature;

use App\Http\Livewire\LanguagePicker;
use Livewire\Livewire;
use Tests\TestCase;

class LanguagePickerTest extends TestCase
{
    public function test_language_can_be_added(): void
    {
        Livewire::test(LanguagePicker::class, ['languages' => ['en'], 'availableLanguages' => ['en', 'fr', 'es', 'de', 'pt']])
            ->call('addLanguage')
            ->assertSet('languages', ['en', '']);
    }

    public function test_no_more_than_five_languages_can_be_added(): void
    {
        Livewire::test(LanguagePicker::class, ['languages' => ['en', 'fr', 'es', 'de', 'pt'], 'availableLanguages' => ['en', 'fr', 'es', 'de', 'pt']])
            ->call('addLanguage')
            ->assertCount('languages', 5);
    }

    public function test_language_can_be_removed(): void
    {
        Livewire::test(LanguagePicker::class, ['languages' => ['en'], 'availableLanguages' => ['en', 'fr', 'es', 'de', 'pt']])
            ->call('removeLanguage', 0)
            ->assertSet('languages', []);
    }
}
