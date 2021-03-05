<!-- Primary Navigation Menu -->
<nav x-data="{ open: false }">
    <!-- Navigation Links -->
    <ul role="list" class="nav">
        @auth
        <x-nav-link :href="localized_route('dashboard')" :active="request()->routeIs(locale() . '.dashboard')">
            {{ __('dashboard.title') }}
        </x-nav-link>
        @else
        @if (Route::has(locale() . '.register'))
        <x-nav-link :href="localized_route('register')">
            {{ __('auth.create_account') }}
        </x-nav-link>
        @endif
        <x-nav-link :href="localized_route('login')">
            {{ __('auth.sign_in') }}
        </x-nav-link>
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
                @if(Auth::user()->consultantProfile)
                <p>
                    <x-dropdown-link href="{{ localized_route('consultant-profiles.show', ['consultantProfile' => Auth::user()->consultantProfile]) }}" :active="request()->routeIs(locale() . '.consultant-profiles.show', ['consultantProfile' => Auth::user()->consultantProfile])">
                        {{ __('consultant-profile.my_profile') }}
                    </x-dropdown-link>
                </p>
                @endif
                <p>
                    <x-dropdown-link href="{{ localized_route('users.edit') }}" :active="request()->routeIs(locale() . '.users.edit')">
                        {{ __('user.my_settings') }}
                    </x-dropdown-link>
                </p>

                <p>
                    <x-dropdown-link href="{{ localized_route('users.admin') }}" :active="request()->routeIs(locale() . '.users.admin')">
                        {{ __('user.my_account') }}
                    </x-dropdown-link>
                </p>

                <!-- Authentication -->
                <form method="POST" action="{{ localized_route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="localized_route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('auth.sign_out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
    @endauth

    <!-- Language Switcher -->
    <x-language-switcher />
</nav>
