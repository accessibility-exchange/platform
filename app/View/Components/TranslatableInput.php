<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TranslatableInput extends Component
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
     * A short label for the input (used to label alternate language fields).
     *
     * @var string
     */
    public string $shortLabel;

    /**
     * The hint for the input.
     *
     * @var string|null
     */
    public ?string $hint;

    /**
     * The locales supported by the input.
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
     * Whether the form input is required.
     *
     * @var bool
     */
    public $required;

    /**
     * The name for asl or lsq content.
     *
     * @var null|string
     */
    public ?string $interpretationName;

    /**
     * The namespace for asl or lsq content.
     *
     * @var null|string
     */
    public ?string $interpretationNameSpace;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label, $hint = null, $model = null, $shortLabel = '', $required = false, $interpretationName = null, $interpretationNameSpace = null)
    {
        $languages = $model->languages ?? config('locales.supported');

        if (($key = array_search(locale(), $languages)) !== false) {
            unset($languages[$key]);
            array_unshift($languages, locale());
        }

        $this->name = $name;
        $this->label = $label;
        $this->shortLabel = $shortLabel ? $shortLabel : $label;
        $this->hint = $hint;
        $this->languages = $languages;
        $this->model = $model;
        $this->required = $required;
        $this->interpretationName = $interpretationName;
        $this->interpretationNameSpace = $interpretationNameSpace;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.translatable-input');
    }
}
