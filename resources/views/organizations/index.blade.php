<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('organization.index_title') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('organization.index_title') }}
        </h1>
    </x-slot>

    <div class="grid">
        @forelse($organizations as $organization)
            <x-card class="community-organization">
                <x-slot name="title">
                    <a href="{{ localized_route('organizations.show', $organization) }}">{{ $organization->name }}</a>
                </x-slot>
                <p>
                    <strong>{{ __('Community organization') }}</strong>
                    @if ($organization->display_roles)
                        <br />
                        <strong class="font-semibold">{{ __('Roles') }}:</strong>
                        @foreach ($organization->display_roles as $role)
                            {{ $role }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    @endif
                </p>

                @if ($organization->hasAddedDetails())
                    <p>
                        <strong class="font-semibold">{{ __('Location') }}:</strong> {{ $organization->locality }},
                        {{ get_region_name($organization->region, ['CA'], locale()) }}@if ($organization->representables)
                            <br />
                            <strong class="font-semibold">{{ __('Communities served') }}:</strong>
                            @foreach ($organization->representables as $community)
                                {{ $community->name }}@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @endif
                    </p>
                @endif
            </x-card>
            @empty
                <p>{{ __('organization.none_found') }}</p>
            @endforelse
        </div>
    </x-app-layout>
