<!-- Primary Navigation Menu -->
<nav x-data="{ open: false }">
    <!-- Navigation Links -->
    <ul role="list" class="nav">
        @auth
        <x-nav-link :href="localized_route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('dashboard.title') }}
        </x-nav-link>
        @else
        <x-nav-link :href="localized_route('login')">
            {{ __('auth.login') }}
        </x-nav-link>
        @if (Route::has(locale() . '.register'))
        <x-nav-link :href="localized_route('register')">
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
                    <x-dropdown-link href="{{ localized_route('users.show', Auth::user()) }}">
                        {{ __('user.your_profile') }}
                    </x-dropdown-link>
                </p>

                <!-- Authentication -->
                <form method="POST" action="{{ localized_route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="localized_route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('auth.logout') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
    @endauth

    <!-- Language Switcher -->
    <x-language-switcher />
</nav>
