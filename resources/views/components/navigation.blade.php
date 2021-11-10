<!-- Primary Navigation Menu -->
<nav x-data="{ open: false }">
    <!-- Navigation Links -->
    <ul role="list" class="nav">
        @auth
        <x-nav-link :href="localized_route('dashboard')" :active="request()->routeIs(locale() . '.dashboard')">
            {{ __('dashboard.title') }}
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
    <div class="browse">
        <x-dropdown>
            <x-slot name="trigger">
                {{ __('Explore') }}
            </x-slot>

            <x-slot name="content">
                <p>
                    <x-dropdown-link :href="localized_route('collections.index')" :active="request()->routeIs(locale() . '.collections.index')">
                        {{ __('Resource hub') }}
                    </x-dropdown-link>
                </p>
                <p>
                    <x-dropdown-link :href="localized_route('consultants.index')" :active="request()->routeIs(locale() . '.consultants.index')">
                        {{ __('consultant.index_title') }}
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
    <!-- User Dropdown -->
    <div class="user">
        <x-dropdown>
            <x-slot name="trigger">
                <x-heroicon-s-user-circle aria-hidden="true" /> {{ Auth::user()->name }}
            </x-slot>

            <x-slot name="content">
                @if(Auth::user()->consultant)
                <p>
                    <x-dropdown-link href="{{ localized_route('consultants.show', ['consultant' => Auth::user()->consultant]) }}" :active="request()->routeIs(locale() . '.consultants.show', Auth::user()->consultant)">
                        {{ __('consultant.my_page') }}
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
