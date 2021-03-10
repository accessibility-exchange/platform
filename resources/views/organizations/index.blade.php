<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('organization.index_title') }}
        </h1>
    </x-slot>

   <div class="flow">
    @if($organizations)
        @foreach($organizations as $organization)
        <article>
            <h2>
                <a href="{{ localized_route('organizations.show', $organization) }}">{{ $organization->name }}</a>
            </h2>
            <p>{{ $organization->locality }}, {{ __('regions.' . $organization->region) }}</p>
        </article>
        @endforeach
    @else
        <p>{{ __('organization.none_found') }}</p>
    @endif
    </div>
</x-app-layout>
