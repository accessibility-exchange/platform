<!-- Primary Navigation Menu -->
<nav class="primary flex align:center" x-data="{ 'open': false }" aria-label="{{ __('main menu') }}" @click.away="open = false">
    <button class="borderless" x-bind:aria-expanded="open.toString()" x-on:click="open = !open" @keyup.escape.window="open = false">
        <x-heroicon-o-menu class="indicator" aria-hidden="true" /><span>{{ __('Menu') }}</span>
    </button>
    <ul role="list">
        @auth
        <li>
            <x-nav-link :href="localized_route('dashboard')" :active="request()->routeIs(locale() . '.dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>
        </li>
        @if(Auth::user()->communityMember || Auth::user()->entity())
        <li>
            <x-nav-link :href="localized_route('projects.index')" :active="request()->routeIs(locale() . '.projects.index')">
                {{ __('Projects') }}
            </x-nav-link>
        </li>
        @endif
        @else
        <li class="account">
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
        <li>
            <x-nav-link :href="localized_route('people-and-organizations')">
                {{ __('People and organizations') }}
            </x-nav-link>
        </li>
        <li>
            <x-nav-link :href="localized_route('collections.index')" :active="request()->routeIs(locale() . '.collections.index')">
                {{ __('Resources and training') }}
            </x-nav-link>
        </li>
        <li class="account">
            <x-nav-link href="{{ localized_route('users.settings') }}" :active="request()->routeIs(locale() . '.users.settings')">
                <x-heroicon-s-user-circle aria-hidden="true" height="20" width="20" /> {{ Auth::user()->name }}
            </x-nav-link>
        </li>
        <!-- Authentication -->
        <li x-data>
            <x-nav-link :href="localized_route('logout')" x-on:click.prevent="$refs.form.submit()">
                {{ __('hearth::auth.sign_out') }}
            </x-nav-link>
            <form method="POST" action="{{ localized_route('logout') }}" x-ref="form">
                @csrf
            </form>
        </li>
        @endauth
    </ul>
</nav>
