<div class="locales">
    <x-dropdown>
        <x-slot name="trigger">
            <x-heroicon-s-globe-alt aria-hidden="true" /> {{ $locales[locale()] }}
        </x-slot>

        <x-slot name="content">
            @foreach ($locales as $key => $locale )
            <p>
                <x-dropdown-link rel="alternate" hreflang="{{ $key }}" :href="current_route($key, route($key . '.welcome'))" :active="request()->routeIs($key . '.*')">
                    {{ $locale }}
                </x-dropdown-link>
            </p>
            @endforeach
        </x-slot>
    </x-dropdown>
</div>
