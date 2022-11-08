<nav class="themes" aria-label="{{ __('themes') }}" x-data="{
    theme: '{{ $theme }}',
    preview(theme) {
        $wire.setTheme(theme);
        this.theme = theme;
        if (this.theme === 'system') {
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.dataset.theme = 'dark';
            } else {
                document.documentElement.dataset.theme = 'light';
            }
        } else {
            document.documentElement.dataset.theme = this.theme;
        }
    }
}">
    <ul role="list">
        <li class="dropdown" x-data="{ open: false }" @keyup.escape.window="open = false" @click.away="open = false"
            @close.stop="open = false">
            <button class="nav-button" @click="open = ! open" x-bind:aria-expanded="open.toString()">
                @svg('tae-contrast') <span>{{ __('Theme') }}</span>
                @svg('heroicon-o-chevron-down', 'indicator')
            </button>

            <fieldset class="theme-switcher" x-cloak>
                <legend class="sr-only">{{ __('Themes') }}</legend>
                <ul role="list">
                    @foreach ($themes as $theme)
                        <li wire:key="theme-{{ $theme['value'] }}">
                            <x-hearth-radio-button name="theme" :value="$theme['value']" wire:model="theme"
                                @click="preview('{{ $theme['value'] }}')" />
                            <x-hearth-label for="theme-{{ $theme['value'] }}">
                                <x-theme-preview class="mb-0 mr-1 h-8 w-8" :for="$theme['value']" /> {{ $theme['label'] }}
                            </x-hearth-label>
                        </li>
                    @endforeach
                </ul>
            </fieldset>
        </li>
    </ul>
</nav>
