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
</header>
