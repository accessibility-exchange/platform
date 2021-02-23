<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('dashboard.title') }}</h1>
    </x-slot>

    <p>{{ __('dashboard.welcome', ['name' => Auth::user()->name]) }}</p>
</x-app-layout>
