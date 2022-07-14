<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\LaravelOptions\Options;

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
    public function __construct($model = null)
    {
        $this->model = $model;
        $this->availableLanguages = Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray();
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
