<!-- Primary Navigation Menu -->
<nav x-data="{ open: false }" aria-label="{{ __('main menu') }}">
    <!-- Navigation Links -->
    <ul role="list" class="nav__basics">
        @auth
        <li>
            <x-nav-link :href="localized_route('dashboard')" :active="request()->routeIs(locale() . '.dashboard')">
                {{ __('My dashboard') }}
            </x-nav-link>
        </li>
        @if(Auth::user()->communityMember || Auth::user()->entity())
        <li>
            <x-nav-link :href="localized_route('users.show_my_projects')" :active="request()->routeIs(locale() . '.users.show_my_projects')">
                {{ __('My projects') }}
            </x-nav-link>
        </li>
        @endif
        @else
        <li>
            <x-nav-link :href="localized_route('register')">
                {{ __('hearth::auth.create_account') }}
            </x-nav-link>
        </li>
        <li>
            <x-nav-link :href="localized_route('login')">
                {{ __('hearth::auth.sign_in') }}
            </x-nav-link>
        </li>
        @endauth

        @auth
        <x-nav-dropdown>
            <x-slot name="trigger">
                {{ __('Explore') }}
            </x-slot>
            <x-slot name="content">
                <li>
                    <x-nav-link :href="localized_route('community-members.index')" :active="request()->routeIs(locale() . '.community-members.index')">
                        {{ __('Community members') }}
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('entities.index')" :active="request()->routeIs(locale() . '.entities.index')">
                        {{ __('Regulated entities') }}
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('projects.index')" :active="request()->routeIs(locale() . '.projects.index')">
                        {{ __('Projects') }}
                    </x-nav-link>
                </li>
            </x-slot>
        </x-nav-dropdown>
        <x-nav-dropdown>
            <x-slot name="trigger">
                {{ __('Resources') }}
            </x-slot>
            <x-slot name="content">
                <li>
                    <x-nav-link :href="localized_route('collections.index')" :active="request()->routeIs(locale() . '.collections.index')">
                        {{ __('Resource hub') }}
                    </x-nav-link>
                </li>
            </x-slot>
        </x-nav-dropdown>
        <x-nav-dropdown>
            <x-slot name="trigger">
                <x-heroicon-s-user-circle aria-hidden="true" /> {{ Auth::user()->name }}
            </x-slot>
            <x-slot name="content">
                @if(Auth::user()->communityMember)
                <li>
                    <x-nav-link href="{{ localized_route('community-members.show', ['communityMember' => Auth::user()->communityMember]) }}" :active="request()->routeIs(locale() . '.community-members.show', Auth::user()->communityMember)">
                        {{ __('My page') }}
                    </x-nav-link>
                </li>
                @endif
                <li>
                    <x-nav-link href="{{ localized_route('users.settings') }}" :active="request()->routeIs(locale() . '.users.settings')">
                        {{ __('Settings') }}
                    </x-nav-link>
                </li>
                <!-- Authentication -->
                <li>
                    <form method="POST" action="{{ localized_route('logout') }}">
                        @csrf
                        <x-nav-link :href="localized_route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('hearth::auth.sign_out') }}
                        </x-nav-link>
                    </form>
                </li>
            </x-slot>
        </x-nav-dropdown>
        @endauth

        <!-- Language Switcher -->
        <x-hearth-language-switcher />
    </ul>

</nav>
