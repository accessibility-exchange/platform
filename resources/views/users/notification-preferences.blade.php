<x-app-layout>
    <x-slot name="title">{{ __('Notification preferences') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('users.settings') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Notification preferences') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    {{-- TODO --}}
    <p>Coming soon!</p>
</x-app-layout>
