<!-- Primary Navigation Menu -->
<nav x-data="{ open: false }">
    <!-- Navigation Links -->
    <ul role="list" class="nav">
        @auth
        <x-nav-link :href="localized_route('dashboard')" :active="request()->routeIs(locale() . '.dashboard')">
            {{ __('dashboard.title') }}
        </x-nav-link>
        <x-nav-link :href="localized_route('resources.index')" :active="request()->routeIs(locale() . '.resources.index')">
            {{ __('resource.index_title') }}
        </x-nav-link>
        @else
        @if (Route::has(locale() . '.registration'))
        <x-nav-link :href="localized_route('registration')">
            {{ __('hearth::auth.create_account') }}
        </x-nav-link>
        @endif
        <x-nav-link :href="localized_route('login')">
            {{ __('hearth::auth.sign_in') }}
        </x-nav-link>
        @endauth
    </ul>

    @auth
    <!-- User Dropdown -->
    <div class="user">
        <x-dropdown>
            <x-slot name="trigger">
                <x-heroicon-s-user-circle aria-hidden="true" /> {{ Auth::user()->name }}
            </x-slot>

            <x-slot name="content">
                @if(Auth::user()->profile)
                <p>
                    <x-dropdown-link href="{{ localized_route('profiles.show', ['profile' => Auth::user()->profile]) }}" :active="request()->routeIs(locale() . '.profiles.show', Auth::user()->profile)">
                        {{ __('profile.my_page') }}
                    </x-dropdown-link>
                </p>
                @endif

                <p>
                    <x-dropdown-link href="{{ localized_route('users.edit') }}" :active="request()->routeIs(locale() . '.users.edit')">
                        {{ __('hearth::user.settings') }}
                    </x-dropdown-link>
                </p>

                <p>
                    <x-dropdown-link href="{{ localized_route('users.admin') }}" :active="request()->routeIs(locale() . '.users.admin')">
                        {{ __('hearth::user.account') }}
                    </x-dropdown-link>
                </p>

                <!-- Authentication -->
                <form method="POST" action="{{ localized_route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="localized_route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('hearth::auth.sign_out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
    @endauth

    <!-- Language Switcher -->
    <x-hearth-language-switcher />
</nav>
