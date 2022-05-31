<x-app-layout>
    <x-slot name="header">
        <h1>@yield('code'): @yield('title')</h1>
    </x-slot>

    <p>@yield('message')</p>
    @auth()
        <p><a href="{{ localized_route('dashboard') }}">{{ __('Return to dashboard') }}</a></p>
    @else
        <p><a href="{{ localized_route('welcome') }}" rel="home">{{ __('hearth::errors.return_home') }}</a></p>
    @endauth
</x-app-layout>
