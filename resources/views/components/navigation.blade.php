<!-- Primary Navigation Menu -->
<nav x-data="{ open: false }" aria-label="{{ __('main menu') }}">
    <!-- Navigation Links -->
    <ul role="list" class="nav__basics">
        @auth
        <x-nav-link :href="localized_route('dashboard')" :active="request()->routeIs(locale() . '.dashboard')">
            {{ __('My dashboard') }}
        </x-nav-link>
        @if(Auth::user()->communityMember || Auth::user()->entity())
        <x-nav-link :href="localized_route('users.show_my_projects')" :active="request()->routeIs(locale() . '.users.show_my_projects')">
            {{ __('My projects') }}
        </x-nav-link>
        @endif
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
    <div class="nav__explore">
        <x-dropdown>
            <x-slot name="trigger">
                {{ __('Explore') }}
            </x-slot>

            <x-slot name="content">
                <p>
                    <x-dropdown-link :href="localized_route('community-members.index')" :active="request()->routeIs(locale() . '.community-members.index')">
                        {{ __('Community members') }}
                    </x-dropdown-link>
                </p>
                <p>
                    <x-dropdown-link :href="localized_route('entities.index')" :active="request()->routeIs(locale() . '.entities.index')">
                        {{ __('Regulated entities') }}
                    </x-dropdown-link>
                </p>
                <p>
                    <x-dropdown-link :href="localized_route('projects.index')" :active="request()->routeIs(locale() . '.projects.index')">
                        {{ __('Projects') }}
                    </x-dropdown-link>
                </p>
            </x-slot>
        </x-dropdown>
    </div>
    <div class="nav__resources">
        <x-dropdown>
            <x-slot name="trigger">
                {{ __('Resources') }}
            </x-slot>

            <x-slot name="content">
                <p>
                    <x-dropdown-link :href="localized_route('collections.index')" :active="request()->routeIs(locale() . '.collections.index')">
                        {{ __('Resource hub') }}
                    </x-dropdown-link>
                </p>
            </x-slot>
        </x-dropdown>
    </div>
    <!-- User Dropdown -->
    <div class="user">
        <x-dropdown>
            <x-slot name="trigger">
                <x-heroicon-s-user-circle aria-hidden="true" /> {{ Auth::user()->name }}
            </x-slot>

            <x-slot name="content">
                @if(Auth::user()->communityMember)
                <p>
                    <x-dropdown-link href="{{ localized_route('community-members.show', ['communityMember' => Auth::user()->communityMember]) }}" :active="request()->routeIs(locale() . '.community-members.show', Auth::user()->communityMember)">
                        {{ __('My page') }}
                    </x-dropdown-link>
                </p>
                @endif

                <p>
                    <x-dropdown-link href="{{ localized_route('users.settings') }}" :active="request()->routeIs(locale() . '.users.settings')">
                        {{ __('Settings') }}
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
