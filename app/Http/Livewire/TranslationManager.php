<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class TranslationManager extends Component
{
    public mixed $model;

    public bool $removingLanguage;

    public bool $addingLanguage;

    public array $availableLanguages = [];

    public string $toRemove;

    public function mount($model): void
    {
        $this->model = $model;
        $this->removingLanguage = false;
        $this->addingLanguage = false;
        $this->availableLanguages = ['' => __('Choose a language…')] + get_available_languages(true);
    }

    public function removeLanguage($toRemove): void
    {
        $this->toRemove = $toRemove;
        $this->removingLanguage = true;
    }

    public function cancelRemoveLanguage(): void
    {
        $this->removingLanguage = false;
    }

    public function addLanguage(): void
    {
        $this->addingLanguage = true;
    }

    public function cancelAddLanguage(): void
    {
        $this->addingLanguage = false;
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.translation-manager');
    }
}
