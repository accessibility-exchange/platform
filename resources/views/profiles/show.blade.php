<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ $profile->name }}
        </h1>
    </x-slot>

    <p>{{ $profile->locality }}, {{ __('regions.' . $profile->region) }}</p>

    @can('update', $profile)
    <p><a href="{{ localized_route('profiles.edit', $profile) }}">{{ __('profile.edit_profile') }}</a></p>
    @endcan
</x-app-layout>
