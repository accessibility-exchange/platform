<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ $organization->name }}
        </h1>
    </x-slot>

    <p>{{ $organization->locality }}, {{ __('regions.' . $organization->region) }}</p>

    @can('update', $organization)
    <p><a href="{{ localized_route('organizations.edit', ['organization' => $organization]) }}">{{ __('organization.edit_organization') }}</a></p>
    @endcan
</x-app-layout>
