<?php

namespace App\Http\Livewire;

use Livewire\Component;

class OtherLinks extends Component
{
    public $links = [];

    public function mount(array $links)
    {
        $this->links = old('other_links', $links);
    }

    public function addLink(): void
    {
        if (! $this->canAddMoreLinks()) {
            return;
        }

        $this->links[] = ['title' => '', 'url' => ''];
    }

    public function removeLink(int $i): void
    {
        unset($this->links[$i]);

        $this->links = array_values($this->links);
    }

    public function canAddMoreLinks()
    {
        return count($this->links) < 6;
    }

    public function render()
    {
        return view('livewire.other-links');
    }
}
