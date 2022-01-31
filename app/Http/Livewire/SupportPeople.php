<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SupportPeople extends Component
{
    public $supportPeople = [];

    public function mount(array $supportPeople)
    {
        $this->supportPeople = old('support_people', $supportPeople);
    }

    public function addPerson(): void
    {
        if (! $this->canAddMorePeople()) {
            return;
        }

        $this->supportPeople[] = ['name' => '', 'phone' => '', 'email' => ''];
    }

    public function removePerson(int $i): void
    {
        unset($this->supportPeople[$i]);

        $this->supportPeople = array_values($this->supportPeople);
    }

    public function canAddMorePeople()
    {
        return count($this->supportPeople) < 6;
    }

    public function render()
    {
        return view('livewire.support-people');
    }
}
