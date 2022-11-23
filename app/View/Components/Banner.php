<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Banner extends Component
{
    public string $type;

    public ?string $icon;

    public function __construct(string $type = 'info', ?string $icon = null)
    {
        $this->type = $type;

        if (! is_null($icon)) {
            $this->icon = $icon;
        } else {
            $this->icon = match ($this->type) {
                'error' => 'heroicon-o-x-circle',
                'warning' => 'heroicon-o-exclamation-circle',
                'success' => 'heroicon-o-check-circle',
                default => 'heroicon-o-information-circle',
            };
        }
    }

    public function render(): View
    {
        return view('components.banner');
    }
}
