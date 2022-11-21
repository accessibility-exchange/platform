<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LanguageSwitcher extends Component
{
    public array $locales;

    public function __construct()
    {
        $locales = config('locales.supported');

        $this->locales = [];

        foreach ($locales as $locale) {
            $this->locales[$locale] = get_language_exonym($locale, $locale);
        }
    }

    public function render(): View
    {
        return view('components.language-switcher');
    }
}
