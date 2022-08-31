<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\LaravelOptions\Options;

class TranslationPicker extends Component
{
    public array $languages;

    public array $availableLanguages;

    public function __construct(?array $languages = null, ?array $availableLanguages = null)
    {
        $this->languages = $languages ?? ['en', 'fr', 'ase', 'fcs'];
        $this->availableLanguages = $availableLanguages ?? Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray();
    }

    public function render(): View
    {
        return view('components.translation-picker');
    }
}
