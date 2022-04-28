<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TranslatableTextarea extends Component
{
    /**
     * The name for the input.
     *
     * @var string
     */
    public string $name;

    /**
     * The label for the input.
     *
     * @var string
     */
    public string $label;

    /**
     * The hint for the input.
     *
     * @var string|null
     */
    public ?string $hint;

    /**
     * The languages supported by the input.
     *
     * @var array
     */
    public array $languages;

    /**
     * The model to which the input field belongs.
     *
     * @var mixed
     */
    public mixed $model;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label, $hint = null, $model = null)
    {
        $languages = $model->languages ?? config('locales.supported');

        if (($key = array_search(locale(), $languages)) !== false) {
            unset($languages[$key]);
            array_unshift($languages, locale());
        }

        $this->name = $name;
        $this->label = $label;
        $this->hint = $hint;
        $this->model = $model;
        $this->languages = $languages;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.translatable-textarea');
    }
}
