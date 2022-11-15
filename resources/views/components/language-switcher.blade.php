<x-nav-dropdown {{ $attributes->merge() }}>
    <x-slot name="trigger">
        {{ __('hearth::nav.languages') }}
    </x-slot>

    <x-slot name="content" x-data>
        @foreach ($locales as $key => $locale)
            <li x-data>
                <x-nav-link hreflang="{{ $key }}" rel="alternate" :href="trans_current_route($key, route($key . '.welcome'))" :active="request()->routeIs($key . '.*') && !$isSignLanguageEnabled"
                    x-on:click.prevent="$refs.form.submit()">
                    {{ $locale }}
                </x-nav-link>
                <form method="POST"
                    action="{{ localized_route('settings.edit-website-accessibility-sign-language-translations') }}"
                    x-ref="form">
                    @csrf
                    @method('patch')
                    <input name="sign_language_translations" type="hidden" value="0">
                    <input name="target" type="hidden"
                        value="{{ trans_current_route($key, route($key . '.welcome')) }}">
                </form>
            </li>
            @if ($pairedSignLanguages[$key])
                <li x-data>
                    <x-nav-link hreflang="{{ $key }}" rel="alternate" :href="trans_current_route($key, route($key . '.welcome'))" :active="request()->routeIs($key . '.*') && $isSignLanguageEnabled"
                        x-on:click.prevent="$refs.form.submit()">
                        {{ __(':signLanguage (with :locale)', ['signLanguage' => __('locales.' . $pairedSignLanguages[$key]), 'locale' => $locale]) }}
                    </x-nav-link>
                    <form method="POST"
                        action="{{ localized_route('settings.edit-website-accessibility-sign-language-translations') }}"
                        x-ref="form">
                        @csrf
                        @method('patch')
                        <input name="sign_language_translations" type="hidden" value="1">
                        <input name="target" type="hidden"
                            value="{{ trans_current_route($key, route($key . '.welcome')) }}">
                    </form>
                </li>
            @endif
        @endforeach
    </x-slot>
</x-nav-dropdown>

{{--

                <x-nav-link :href="localized_route('logout')" x-on:click.prevent="$refs.form.submit()">
                    {{ __('Sign out') }}
                </x-nav-link>
                <form method="POST" action="{{ localized_route('logout') }}" x-ref="form">
                    @csrf
                </form>

 --}}
