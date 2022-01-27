<?php

namespace App\Http\Livewire;

use Livewire\Component;

class WorkAndVolunteerExperiences extends Component
{
    public $experiences = [];

    public function mount(array $experiences)
    {
        $this->experiences = old('work_and_volunteer_experiences', $experiences);
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
        return count($this->experiences) < 21;
    }

    public function render()
    {
        return view('livewire.work-and-volunteer-experiences');
    }
}
