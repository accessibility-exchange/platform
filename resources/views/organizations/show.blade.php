<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ $organization->name }}
        </h1>
    </x-slot>

    <p>{{ $organization->locality }}, {{ get_region_name($organization->region, ["CA"], locale()) }}</p>

    @can('update', $organization)
    <p><a href="{{ localized_route('organizations.edit', $organization) }}">{{ __('organization.edit_organization') }}</a></p>
    @endcan
</x-app-layout>
