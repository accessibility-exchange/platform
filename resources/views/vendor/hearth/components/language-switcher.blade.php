<x-nav-dropdown>
    <x-slot name="trigger">
        <x-heroicon-s-globe-alt aria-hidden="true" /> {{ $locales[locale()] }}
    </x-slot>

    <x-slot name="content">
        @foreach ($locales as $key => $locale )
        <li>
            <x-nav-link rel="alternate" hreflang="{{ $key }}" :href="current_route($key, route($key . '.welcome'))" :active="request()->routeIs($key . '.*')">
                {{ $locale }}
            </x-nav-link>
        </li>
        @endforeach
    </x-slot>
</x-nav-dropdown>
