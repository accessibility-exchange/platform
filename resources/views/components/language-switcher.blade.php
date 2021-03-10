<div class="locales">
    <x-dropdown>
        <x-slot name="trigger">
            <x-heroicon-s-globe-alt aria-hidden="true" /> {{ $locales[locale()]['name'] }}
        </x-slot>

        <x-slot name="content">
            @foreach ($locales as $key => $locale )
            <p>
                <x-dropdown-link rel="alternate" hreflang="{{ $locale['code'] }}" :href="current_route($key)" :active="request()->routeIs($key . '.*')">
                    {{ $locale['name'] }}
                </x-dropdown-link>
            </p>
            @endforeach
        </x-slot>
    </x-dropdown>
</div>
