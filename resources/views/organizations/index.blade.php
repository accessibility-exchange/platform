<x-app-wide-layout>
    <x-slot name="title">{{ __('organization.index_title') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('organization.index_title') }}
        </h1>
    </x-slot>

   <div class="grid">
        @forelse($organizations as $organization)
        <article class="box stack card community-organization">
            <h2 class="h3">
                <a href="{{ localized_route('organizations.show', $organization) }}">{{ $organization->name }}</a>
            </h2>
            <p>
                <strong>{{ __('Community organization') }}</strong>@if($organization->roles)<br />
                <strong class="weight:semibold">{{__('Roles:') }}</strong> @foreach($organization->roles as $role){{ $role->name }}@if(!$loop->last), @endif @endforeach @endif
            </p>

            <p>
                <strong class="weight:semibold">{{__('Location:') }}</strong> {{ $organization->locality }}, {{ get_region_name($organization->region, ["CA"], locale()) }}@if($organization->representables)<br />
                <strong class="weight:semibold">{{__('Communities served:') }}</strong> @foreach($organization->representables as $community){{ $community->name }}@if(!$loop->last), @endif @endforeach @endif
            </p>

        </article>
        @empty
        <p>{{ __('organization.none_found') }}</p>
        @endforelse
    </div>
</x-app-wide-layout>
