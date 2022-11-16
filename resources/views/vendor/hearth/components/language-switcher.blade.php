<x-nav-dropdown {{ $attributes->merge() }}>
    <x-slot name="trigger">
        @svg('heroicon-o-translate') <span>{{ __('Language') }}</span>
    </x-slot>

    <x-slot name="content">
        @foreach ($locales as $key => $locale)
            <li>
                <x-nav-link hreflang="{{ $key }}" rel="alternate" :href="trans_current_route($key, route($key . '.welcome'))" :active="request()->routeIs($key . '.*')">
                    {{ $locale }}
                </x-nav-link>
            </li>
        @endforeach
    </x-slot>
</x-nav-dropdown>
