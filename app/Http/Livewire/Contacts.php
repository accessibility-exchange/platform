<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Contacts extends Component
{
    public $contacts = [];

    public function mount(array $contacts)
    {
        $this->contacts = old('contacts', $contacts);
    }

    public function addContact(): void
    {
        if (! $this->canAddMoreContacts()) {
            return;
        }

        $this->contacts[] = ['name' => '', 'phone' => '', 'email' => ''];
    }

    public function removeContact(int $i): void
    {
        unset($this->contacts[$i]);

        $this->contacts = array_values($this->contacts);
    }

    public function canAddMoreContacts()
    {
        return count($this->contacts) < 5;
    }

    public function render()
    {
        return view('livewire.contacts');
    }
}
