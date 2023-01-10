<x-app-layout>
    <x-slot name="title">{{ __('Matching') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('dashboard') }}">{{ __('My dashboard') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
            <li><a href="{{ localized_route('settings.show-matching') }}">{{ __('Matching') }}</a></li>
        </ol>
        <h1>
            {{ __('Add or edit your matching information') }}
        </h1>
    </x-slot>

</x-app-layout>
