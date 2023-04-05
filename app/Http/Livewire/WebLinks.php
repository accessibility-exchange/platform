<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class WebLinks extends Component
{
    public array $links;

    public string $name;

    public function mount(array $links, string $name = 'web_links'): void
    {
        $this->name = $name;
        $this->links = old($this->name, $links);
    }

    public function addLink(): void
    {
        if (! $this->canAddMoreLinks()) {
            return;
        }

        $this->links[] = ['title' => '', 'url' => ''];
    }

    /**
     * @param  int  $i The index of the link to remove.
     */
    public function removeLink(int $i): void
    {
        unset($this->links[$i]);

        $this->links = array_values($this->links);
    }

    public function canAddMoreLinks(): bool
    {
        return count($this->links) < 5;
    }

    public function render(): View
    {
        return view('livewire.web-links');
    }
}
