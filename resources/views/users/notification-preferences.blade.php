<x-app-layout>
    <x-slot name="title">{{ __('Notification preferences') }}</x-slot>
    <x-slot name="header">
        <p class="breadcrumb"><x-heroicon-o-chevron-left width="24" height="24" /><a href="{{ localized_route('users.settings') }}">{{ __('Settings') }}</a></p>
        <h1>
            {{ __('Notification preferences') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>TODO.</p>
</x-app-layout>
