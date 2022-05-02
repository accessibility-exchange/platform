<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TranslationManager extends Component
{
    /**
     * The model to which the translations belong.
     *
     * @var mixed
     */
    public mixed $model;

    /**
     * The languages available for translation.
     *
     * @var array
     */
    public array $availableLanguages = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($model = null, $availableLanguages = null)
    {
        $this->model = $model;
        $this->availableLanguages = ['' => __('Choose a language…')] + get_available_languages(true);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.translation-manager');
    }
}
