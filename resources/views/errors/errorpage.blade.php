<x-app-layout>
    <x-slot name="header">
        <h1>@yield('code'): @yield('title')</h1>
    </x-slot>

    <p>@yield('message')</p>
    <p><a href="/" rel="home">{{ __('Return to home page') }}</a></p>
</x-app-layout>
