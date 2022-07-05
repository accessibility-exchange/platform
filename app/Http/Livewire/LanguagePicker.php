<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LanguagePicker extends Component
{
    public string $name;

    public array $languages = [];

    public array $availableLanguages = [];

    public function mount(array $languages, string $name = 'languages')
    {
        $this->name = $name;
        $this->languages = old($this->name, $languages);
    }

    public function addLanguage(): void
    {
        if (! $this->canAddMoreLanguages()) {
            return;
        }

        $this->languages[] = '';
    }

    public function removeLanguage(int $i): void
    {
        unset($this->languages[$i]);
    }

    public function canAddMoreLanguages()
    {
        return count($this->languages) < 5;
    }

    public function render()
    {
        return view('livewire.language-picker');
    }
}
