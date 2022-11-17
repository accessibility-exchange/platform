<?php

namespace App\View\Components;

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
            $this->locales[$locale] = get_language_exonym($locale, $locale);
        }
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
