<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DateInput extends Component
{
    /**
     * The input label.
     *
     * @var string
     */
    public $label;

    /**
     * The input name.
     *
     * @var string
     */
    public $name;

    /**
     * The input value.
     *
     * @var string
     */
    public $value;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $name, $value)
    {
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.date-input');
    }
}
