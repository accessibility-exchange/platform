<header role="banner">
    @include('components.skip-link')
    @include('partials.contact-bar')
    <div class="center center:wide">
        <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
            @include('components.brand')
            <!-- Language Switcher -->
            <nav class="languages" aria-label="{{ __('languages') }}">
                <ul role="list">
                    <x-hearth-language-switcher />
                </ul>
            </nav>
        </div>
    </div>
    <div class="center center:wide">
        @include('components.navigation')
    </div>
</header>
