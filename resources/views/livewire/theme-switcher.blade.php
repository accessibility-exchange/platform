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
        <x-nav-dropdown>
            <x-slot name="trigger">
                @svg('tae-contrast') <span>{{ __('Theme') }}</span>
            </x-slot>

            <x-slot name="content">
                @foreach ($themes as $theme)
                    <li class="w-60" wire:key="theme-{{ $theme['value'] }}">
                        <button class="nav-button" @click="preview('{{ $theme['value'] }}')"
                            x-bind:aria-pressed="$wire.theme === '{{ $theme['value'] }}'">{{ $theme['label'] }}</button>
                    </li>
                @endforeach
            </x-slot>
        </x-nav-dropdown>
    </ul>
</nav>
