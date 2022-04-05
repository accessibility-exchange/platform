<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TeamTrainings extends Component
{
    public $trainings = [];

    public function mount(array $trainings)
    {
        $this->trainings = old('trainings', $trainings);
    }

    public function addTraining(): void
    {
        if (! $this->canAddMoreTrainings()) {
            return;
        }

        $this->trainings[] = ['name' => '', 'date' => '', 'trainer_name' => '', 'trainer_url' => ''];
    }

    public function removeTraining(int $i): void
    {
        unset($this->trainings[$i]);

        $this->trainings = array_values($this->trainings);
    }

    public function canAddMoreTrainings()
    {
        return count($this->trainings) < 5;
    }

    public function render()
    {
        return view('livewire.team-trainings');
    }
}
