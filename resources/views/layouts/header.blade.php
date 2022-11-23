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
            @include('components.navigation')
        </div>
    </div>
    @stack('banners')
    @env('dev')
    <x-banner type="warning">
        {!! Str::inlineMarkdown(
            __(
                '**CAUTION!** This website is under active development. The database is reset nightly, and data you enter will not be preserved.',
            ),
        ) !!}
    </x-banner>
    @endenv
    @if (auth()->hasUser() &&
        auth()->user()->checkStatus('suspended'))
        <x-banner type="error">
            {!! Str::inlineMarkdown(
                __('Your account has been suspended. Please [contact](:url) us if you need further assistance.', [
                    'url' => '#contact',
                ]),
            ) !!}
        </x-banner>
    @endif
</header>
