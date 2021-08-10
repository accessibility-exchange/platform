<x-app-layout>
    <x-slot name="header">
        <h1>@yield('code'): @yield('title')</h1>
    </x-slot>

    <p>@yield('message')</p>
    <p><a href="{{ localized_route('welcome') }}" rel="home">{{ __('hearth::errors.return_home') }}</a></p>
</x-app-layout>
