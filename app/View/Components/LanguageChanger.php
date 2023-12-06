<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LanguageChanger extends Component
{
    public mixed $model;

    public string $currentLanguage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(mixed $model, ?string $currentLanguage)
    {
        $this->model = $model;
        $this->currentLanguage = $currentLanguage ?? locale();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.language-changer');
    }
}
