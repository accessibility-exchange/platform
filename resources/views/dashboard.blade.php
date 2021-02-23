<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('dashboard.title') }}</h1>
    </x-slot>

    @if(session('verified'))
        <x-alert type="success" :title="__('auth.verification_completed')">
            <p>{{ __('auth.verification_completed_message') }}</p>
        </x-alert>
    @endif
    <p>{{ __('dashboard.welcome', ['name' => Auth::user()->name]) }}</p>
</x-app-layout>
