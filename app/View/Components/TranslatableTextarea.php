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
     * The locales supported by the input.
     *
     * @var array
     */
    public array $locales;

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
    public function __construct($name, $label, $locales = null, $model = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->locales = $locales ?? config('locales.supported');
        $this->model = $model;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render(): View
    {
        return view('components.translatable-textarea');
    }
}
