<header role="banner">
    @include('components.skip-link')
    @include('partials.help-bar')
    <div class="center center:wide">
        <div class="nav">
            @include('components.brand')

            @if (!request()->localizedRouteIs('settings.edit-website-accessibility-preferences'))
                <!-- Theme Switcher -->
                <livewire:theme-switcher />
            @endif
            @if (!request()->localizedRouteIs('settings.edit-language-preferences'))
                <!-- Language Switcher -->
                <nav class="languages" aria-label="{{ __('languages') }}">
                    <ul role="list">
                        <x-language-switcher />
                    </ul>
                </nav>
            @endif
            {{-- Don't show if no session available (i.e on error pages like 404) --}}
            @unless(session()->missing('_token'))
                @include('components.navigation')
            @endunless
        </div>
    </div>
    @stack('banners')
    @env('dev')
    <x-banner type="warning">
        {{ safe_inlineMarkdown('**CAUTION!** This website is under active development. The database is reset nightly, and data you enter will not be preserved.') }}
    </x-banner>
    @endenv
    @if (auth()->hasUser() &&
        auth()->user()->checkStatus('suspended'))
        <x-banner type="error">
            {{ safe_inlineMarkdown(
                'Your account has been suspended. Please [contact](:url) us if you need further assistance.',
                ['url' => '#contact'],
            ) }}
        </x-banner>
    @endif
</header>
