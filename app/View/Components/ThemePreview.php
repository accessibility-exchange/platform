<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ThemePreview extends Component
{
    public string $for;

    public function __construct(string $for)
    {
        $this->for = $for;
    }

    public function render(): View
    {
        $foreground = 'var(--theme-body-color)';
        $background = 'var(--theme-body-background)';

        if ($this->for === 'light') {
            $foreground = 'var(--color-graphite-7)';
            $background = 'var(--color-grey-1)';
        }

        return view('components.theme-preview', [
            'foreground' => $foreground,
            'background' => $background,
        ]);
    }
}
