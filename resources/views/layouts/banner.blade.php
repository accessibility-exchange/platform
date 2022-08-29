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
    @env('dev')
    <div
        class="flex h-auto w-full items-center border-x-0 border-t-2 border-b-0 border-solid border-t-graphite-5 bg-yellow-3 p-4">
        <div class="center center:wide">
            <p class="flex flex-wrap items-center justify-center text-xl">
                <x-heroicon-s-exclamation class="mr-2 h-6 w-6" /> <span><strong>CAUTION!</strong> This website is under
                    active development. The database is reset nightly, and data you enter will not be preserved.</span>
            </p>
        </div>
    </div>
    @endenv
</header>
