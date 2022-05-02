<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class WebLinks extends Component
{
    public string $name;

    public array $links;

    /**
     * @param array $links
     *
     * @return void
     */
    public function mount(array $links): void
    {
        $this->name = $name ?? 'web_links';
        $this->links = old($this->name, $links);
    }

    /**
     * @return void
     */
    public function addLink(): void
    {
        if (! $this->canAddMoreLinks()) {
            return;
        }

        $this->links[] = ['title' => '', 'url' => ''];
    }

    /**
     * @param int $i The index of the link to remove.
     *
     * @return void
     */
    public function removeLink(int $i): void
    {
        unset($this->links[$i]);

        $this->links = array_values($this->links);
    }

    /**
     * @return bool
     */
    public function canAddMoreLinks(): bool
    {
        return count($this->links) < 5;
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.web-links');
    }
}
