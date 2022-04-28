<?php

namespace App\Http\Livewire;

use CommerceGuys\Intl\Language\LanguageRepository;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TranslationManager extends Component
{
    public mixed $model;

    public array $availableLanguages = [];

    public function mount($model)
    {
        $this->model = $model;

        $languages = (new LanguageRepository)->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key] = $language->getName();
        }

        $languages = $languages + [
            'ase' => __('American Sign Language'),
            'fcs' => __('Quebec Sign Language'),
        ];

        $this->availableLanguages = ['' => __('Choose a language…')] + $languages;
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.translation-manager');
    }
}
