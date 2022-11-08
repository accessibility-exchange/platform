<?php

namespace App\Http\Livewire;

use App\Enums\Theme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Livewire\Component;
use Spatie\LaravelOptions\Options;

class ThemeSwitcher extends Component
{
    public array $themes = [];

    public string $theme = 'system';

    public function mount()
    {
        $this->themes = Options::forEnum(Theme::class)->toArray();
        if (Auth::user()) {
            $this->theme = Auth::user()->theme;
        } else {
            $this->theme = Cookie::get('theme', 'system');
        }
    }

    public function render()
    {
        return view('livewire.theme-switcher');
    }

    public function setTheme(string $theme)
    {
        $this->theme = $theme;

        if (Auth::user()) {
            Auth::user()->update(['theme' => $this->theme]);
        }

        Cookie::queue('theme', $this->theme);
    }
}
