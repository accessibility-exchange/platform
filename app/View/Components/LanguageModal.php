<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class LanguageModal extends Component
{
    public array $languages;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->languages = $this->assembleLanguages();
    }

    public function assembleLanguages(): array
    {
        $written = [];
        $signed = [];

        foreach (config('locales.supported') as $locale) {
            if (is_signed_language($locale)) {
                $signed[$locale] = Str::upper($locale);
            } else {
                $written[$locale] = get_language_exonym($locale, $locale);
            }
        }

        return $written + $signed;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.language-modal');
    }
}
