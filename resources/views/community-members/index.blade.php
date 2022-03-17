<x-app-wide-layout>
    <x-slot name="title">{{ __('Community members') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Community members') }}
        </h1>
    </x-slot>

   <div class="grid">
        @forelse($communityMembers as $communityMember)
        <x-card class="community-member">
            <x-slot name="title">
                <a href="{{ localized_route('community-members.show', $communityMember) }}">{{ $communityMember->name }}</a>
            </x-slot>
            <p>
                <strong>{{ __('Community member') }}</strong>@if($communityMember->roles)<br />
                <strong class="weight:semibold">{{ __('Role') }}:</strong> @foreach($communityMember->roles as $role){{ Str::title($role) }}@if(!$loop->last), @endif @endforeach @endif
                {{-- TODO: fix roles --}}
            </p>
            <p>
                <strong class="weight:semibold">{{ __('Location') }}:</strong> {{ $communityMember->locality }}, {{ get_region_name($communityMember->region, ["CA"], locale()) }}
            </p>
        </x-card>
        @empty
        <p>{{ __('No community members found.') }}</p>
        @endforelse
    </div>
</x-app-wide-layout>
