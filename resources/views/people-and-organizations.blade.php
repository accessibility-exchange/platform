<x-app-layout>
    <x-slot name="title">{{ __('People and organizations') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('People and organizations') }}</h1>

    </x-slot>

    <h2>{{ __('Individuals') }}</h2>
    <p><a href="{{ localized_route('individuals.index') }}">{{ __('Browse individuals') }}</a></p>

    <h2>{{ __('Regulated Organizations') }}</h2>
    <p><a href="{{ localized_route('regulated-organizations.index') }}">{{ __('Browse regulated organizations') }}</a>
    </p>

    <h2>{{ __('Community Organizations') }}</h2>
    <p><a href="{{ localized_route('organizations.index') }}">{{ __('Browse community organizations') }}</a></p>
</x-app-layout>
