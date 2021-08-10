<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('organization.index_title') }}
        </h1>
    </x-slot>

   <div class="flow">
    @forelse($organizations as $organization)
    <article>
        <h2>
            <a href="{{ localized_route('organizations.show', $organization) }}">{{ $organization->name }}</a>
        </h2>
        <p>{{ $organization->locality }}, {{ get_region_name($organization->region, ["CA"], locale()) }}</p>
    </article>
    @empty
    <p>{{ __('organization.none_found') }}</p>
    @endforelse
    </div>
</x-app-layout>
