<x-app-layout>
    <x-slot name="title">{{ $organization->name }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ $organization->name }}
        </h1>
    </x-slot>

    <div class="meta repel">
        <span>
            {{ $organization->locality }}, {{ get_region_name($organization->region, ["CA"], locale()) }}
        </span>
        @can('block', $organization)
            <x-block-modal :blockable="$organization" />
        @endcan
    </div>

    @can('update', $organization)
    <p><a href="{{ localized_route('organizations.edit', $organization) }}">{{ __('organization.edit_organization') }}</a></p>
    @endcan
</x-app-layout>
