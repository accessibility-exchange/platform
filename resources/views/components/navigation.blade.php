<!-- Primary Navigation Menu -->
<nav x-data="{ open: false }">
<!-- Navigation Links -->
<ul role="list" class="nav">
    @auth
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>
    @else
    <x-nav-link :href="route('login')">
        {{ __('Login') }}
    </x-nav-link>
    @if (Route::has('register'))
    <x-nav-link :href="route('register')">
        {{ __('Register') }}
    </x-nav-link>
    @endif
    @endauth
</ul>

@auth
<!-- Settings Dropdown -->
<div class="settings">
    <x-dropdown>
        <x-slot name="trigger">
            {{ Auth::user()->name }}
        </x-slot>

        <x-slot name="content">
            <x-dropdown-link href="{{ route('users.show', Auth::user()) }}">
                {{ __('Your Profile') }}
            </x-dropdown-link>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Logout') }}
                </x-dropdown-link>
            </form>
        </x-slot>
    </x-dropdown>
</div>
@endauth
</nav>
