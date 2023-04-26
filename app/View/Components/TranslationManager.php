<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\LaravelOptions\Options;

class TranslationManager extends Component
{
    /**
     * The model to which the translations belong.
     */
    public mixed $model;

    /**
     * The languages available for translation.
     */
    public array $availableLanguages = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($model = null)
    {
        $this->model = $model;
        $this->availableLanguages = Options::forArray(get_available_languages(true, false))->nullable(__('Choose a language…'))->toArray();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.translation-manager');
    }
}
