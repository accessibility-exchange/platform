<?php

namespace App\View\Components;

use Hearth\Traits\AriaDescribable;
use Hearth\Traits\HandlesValidation;
use Illuminate\View\Component;
use Illuminate\View\View;

class PasswordInput extends Component
{
    use AriaDescribable;
    use HandlesValidation;

    /**
     * The name of the form input.
     */
    public string $name;

    /**
     * The id of the form input.
     *
     * @var null|string
     */
    public mixed $id;

    /**
     * The error bag associated with the form input.
     *
     * @var null|string
     */
    public mixed $bag;

    /**
     * Whether the form input has validation errors.
     */
    public bool $invalid;

    /**
     * Whether the form input has a hint associated with it, or the id of the hint.
     *
     * @var bool|string
     */
    public mixed $hinted;

    /**
     * Whether the form input is disabled.
     *
     * @var bool
     */
    public mixed $disabled;

    /**
     * Whether the form input is required.
     *
     * @var bool
     */
    public mixed $required;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name,
        $id = null,
        $bag = 'default',
        $hinted = false,
        $required = false,
        $disabled = false,
    ) {
        $this->name = $name;
        $this->id = $id ?? $this->name;
        $this->bag = $bag;
        $this->hinted = $hinted;
        $this->invalid = $this->hasErrors($this->name, $this->bag);
        $this->required = $required;
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.password-input');
    }
}
