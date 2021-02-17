<!-- Primary Navigation Menu -->
<nav x-data="{ open: false }">
    <!-- Navigation Links -->
    <ul role="list" class="nav">
        @auth
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('dashboard.title') }}
        </x-nav-link>
        @else
        <x-nav-link :href="route('login')">
            {{ __('auth.login') }}
        </x-nav-link>
        @if (Route::has('register'))
        <x-nav-link :href="route('register')">
            {{ __('auth.register') }}
        </x-nav-link>
        @endif
        @endauth
    </ul>

    @auth
    <!-- Settings Dropdown -->
    <div class="settings">
        <x-dropdown>
            <x-slot name="trigger">
                <x-heroicon-s-user-circle aria-hidden="true" /> {{ Auth::user()->name }}
            </x-slot>

            <x-slot name="content">
                <p>
                    <x-dropdown-link href="{{ route('users.show', Auth::user()) }}">
                        {{ __('user.your_profile') }}
                    </x-dropdown-link>
                </p>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('auth.logout') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
    @endauth

    <!-- Locales Dropdown -->
    <div class="locales">
        <x-dropdown>
            <x-slot name="trigger">
                <x-heroicon-s-globe-alt aria-hidden="true" /> {{ LaravelLocalization::getCurrentLocaleNative() }}
            </x-slot>

            <x-slot name="content">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <p>
                    <x-dropdown-link rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}">
                        {{ $properties['native'] }}
                    </x-dropdown-link>
                </p>
                @endforeach
            </x-slot>
        </x-dropdown>
    </div>
</nav>
