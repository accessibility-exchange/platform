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
    @env(['dev', 'local'])
        <div class="bg-yellow-3 p-4 h-auto w-full border-solid border-t-2 border-b-0 border-x-0 border-t-graphite-5 flex items-center">
            <div class="center center:wide ">
                <p class="text-xl flex flex-wrap justify-center items-center"><x-heroicon-s-exclamation class="w-6 h-6 mr-2" /> <span><strong>CAUTION!</strong> This website is under active development. The database is reset nightly, and data you enter will not be preserved.</span></p>
            </div>
        </div>
    @endenv
</header>
