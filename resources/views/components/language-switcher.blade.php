<x-nav-dropdown {{ $attributes->merge() }}>
    <x-slot name="trigger">
        @svg('tae-language', 'icon--2xl') <span>{{ __('hearth::nav.languages') }}</span>
    </x-slot>

    <x-slot name="content">
        @foreach ($locales as $key => $locale)
            <li>
                <x-nav-link hreflang="{{ get_written_language_for_signed_language($key) }}" rel="alternate"
                    :href="trans_current_route($key, route($key . '.welcome'))" :active="request()->routeIs($key . '.*')">
                    @if (is_signed_language($key))
                        {{ trans(':signLanguage (with :locale)', ['signLanguage' => get_language_exonym($key, $key), 'locale' => get_language_exonym(get_written_language_for_signed_language($key), $key)], $key) }}
                    @else
                        {{ $locale }}
                    @endif
                </x-nav-link>
            </li>
        @endforeach
    </x-slot>
</x-nav-dropdown>
