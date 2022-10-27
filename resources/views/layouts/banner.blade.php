<header role="banner">
    @include('components.skip-link')
    @include('partials.help-bar')
    <div class="center center:wide">
        <div class="nav">
            @include('components.brand')
            <!-- Language Switcher -->
            <nav class="languages" aria-label="{{ __('languages') }}">
                <ul role="list">
                    <x-hearth-language-switcher />
                </ul>
            </nav>
            @include('components.navigation')
        </div>
    </div>
    @stack('banners')
    @env('dev')
    <div class="banner banner--warning">
        <div class="center center:wide">
            <p>
                <x-heroicon-s-exclamation-circle class="mr-2 h-6 w-6" /> <span><strong>CAUTION!</strong> This website is
                    under
                    active development. The database is reset nightly, and data you enter will not be preserved.</span>
            </p>
        </div>
    </div>
    @endenv
    @if (auth()->hasUser() &&
        auth()->user()->checkStatus('suspended'))
        <div class="banner banner--error">
            <div class="center center:wide">
                <p>
                    <x-heroicon-s-no-symbol class="mr-2 h-6 w-6" /> <span>{!! Str::inlineMarkdown(
                        __('Your account has been suspended. Please [contact](:url) us if you need further assistance.', [
                            'url' => '#contact',
                        ]),
                    ) !!}</span>
                </p>
            </div>
        </div>
    @endif
</header>
