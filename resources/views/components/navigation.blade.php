<!-- Primary Navigation Menu -->
<nav class="primary flex align:center" x-data="{ 'open': false }" aria-label="{{ __('main menu') }}" @click.away="open = false">
    <button class="borderless" x-bind:aria-expanded="open.toString()" x-on:click="open = !open" @keyup.escape.window="open = false">
        <x-heroicon-o-menu class="indicator" aria-hidden="true" /><span>{{ __('Menu') }}</span>
    </button>
    <ul role="list">
        @auth
        <li>
            <x-nav-link :href="localized_route('dashboard')" :active="request()->localizedRouteIs('dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>
        </li>
        <li>
            <x-nav-link :href="localized_route('projects.my-projects')" :active="request()->localizedRouteIs('projects.my-projects')">
                {{ __('Projects') }}
            </x-nav-link>
        </li>
        @else
        <li class="account">
            <x-nav-link :href="localized_route('register')">
                {{ __('Create an account') }}
            </x-nav-link>
        </li>
        <li>
            <x-nav-link :href="localized_route('login')">
                {{ __('Sign in') }}
            </x-nav-link>
        </li>
        @endauth

        @auth
        <li>
            <x-nav-link :href="localized_route('people-and-organizations')">
                {{ __('People and organizations') }}
            </x-nav-link>
        </li>
        <li>
            <x-nav-link :href="localized_route('resource-collections.index')" :active="request()->localizedRouteIs('resource-collections.index')">
                {{ __('Resources and training') }}
            </x-nav-link>
        </li>
        <li class="account">
            <x-nav-link href="{{ localized_route('settings.show') }}" :active="request()->localizedRouteIs('users.settings')">
                <x-heroicon-s-user-circle aria-hidden="true" height="20" width="20" /> {{ Auth::user()->name }}
            </x-nav-link>
        </li>
        <!-- Authentication -->
        <li x-data>
            <x-nav-link :href="localized_route('logout')" x-on:click.prevent="$refs.form.submit()">
                {{ __('Sign out') }}
            </x-nav-link>
            <form method="POST" action="{{ localized_route('logout') }}" x-ref="form">
                @csrf
            </form>
        </li>
        @endauth
    </ul>
</nav>
