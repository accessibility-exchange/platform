<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Experiences extends Component
{
    public string $name;

    public array $experiences = [];

    public function mount(array $experiences, $name = 'experiences')
    {
        $this->name = $name;
        $this->experiences = old($this->name, $experiences);
    }

    public function addExperience(): void
    {
        if (! $this->canAddMoreExperiences()) {
            return;
        }

        $this->experiences[] = ['title' => '', 'start_year' => '', 'end_year' => '', 'current' => false];
    }

    public function removeExperience(int $i): void
    {
        unset($this->experiences[$i]);

        $this->experiences = array_values($this->experiences);
    }

    public function canAddMoreExperiences()
    {
        return count($this->experiences) < 20;
    }

    public function render()
    {
        return view('livewire.experiences');
    }
}
