<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class TranslationManager extends Component
{
    public mixed $model;

    public array $availableLanguages = [];

    public function mount($model)
    {
        $this->model = $model;

        $this->availableLanguages = ['' => __('Choose a language…')] + get_available_languages(true);
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.translation-manager');
    }
}
