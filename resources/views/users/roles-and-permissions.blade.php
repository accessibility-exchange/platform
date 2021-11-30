<x-app-layout>
    <x-slot name="title">{{ __('Roles and permissions') }}</x-slot>
    <x-slot name="header">
        <p class="breadcrumb"><x-heroicon-o-chevron-left width="24" height="24" /><a href="{{ localized_route('users.settings') }}">{{ __('Settings') }}</a></p>
        <h1>
            {{ __('Roles and permissions') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')
    {{-- TODO --}}
    <p>Coming soon!</p>
</x-app-layout>
