<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Cookie;
use Illuminate\View\Component;

class LanguageSwitcher extends Component
{
    /**
     * The list of locales.
     *
     * @var array
     */
    public $locales;

    /**
     * The list of paired sign languages.
     *
     * @var array
     */
    public $pairedSignLanguages;

    /**
     * Whether or not sign language is enabled
     *
     * @var bool
     */
    public $isSignLanguageEnabled;

    /**
     * The route targeted by links in the language switcher.
     *
     * @var string
     */
    public $target;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $locales = config('locales.supported', [
            'en',
            'fr',
        ]);

        $this->locales = [];

        foreach ($locales as $locale) {
            $this->locales[$locale] = get_locale_name($locale, $locale);
        }

        $this->pairedSignLanguages = config('locales.paired_sign_language') ?? [];
        $this->isSignLanguageEnabled = auth()->check() ? auth()->user()->sign_language_translations : Cookie::get('sign_language_translations', false);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.language-switcher');
    }
}
