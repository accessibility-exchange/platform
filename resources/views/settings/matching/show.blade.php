<x-app-layout>
    <x-slot name="title">{{ __('Matching') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('dashboard') }}">{{ __('My dashboard') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Matching') }}
        </h1>
    </x-slot>

    <ul class="link-list" role="list">
        <li><a href="{{ localized_route('settings.show-how') }}">{{ __('How matching works') }}</a>
        </li>
        <li><a
                href="{{ localized_route('settings.edit-matching') }}">{{ __('Add or edit your matching information') }}</a>
        </li>
    </ul>
</x-app-layout>
