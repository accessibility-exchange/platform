<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SupportPeople extends Component
{
    public array $people = [];

    public function mount(array $people)
    {
        $this->people = old('support_people', $people);
    }

    public function addPerson(): void
    {
        if (! $this->canAddMorePeople()) {
            return;
        }

        $this->people[] = ['name' => '', 'phone' => '', 'email' => ''];
    }

    public function removePerson(int $i): void
    {
        unset($this->people[$i]);

        $this->people = array_values($this->people);
    }

    public function canAddMorePeople()
    {
        return count($this->people) < 5;
    }

    public function render()
    {
        return view('livewire.support-people');
    }
}
