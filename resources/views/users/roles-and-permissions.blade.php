<x-app-layout>
    <x-slot name="title">{{ __('Roles and permissions') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('users.settings') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Roles and permissions') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    {{-- TODO --}}
    <p>Coming soon!</p>
</x-app-layout>
