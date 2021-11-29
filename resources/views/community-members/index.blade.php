<x-app-layout>
    <x-slot name="title">{{ __('Community members') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Community members') }}
        </h1>
    </x-slot>

   <div class="flow">
        @forelse($communityMembers as $communityMember)
        <article>
            <h2>
                <a href="{{ localized_route('community-members.show', $communityMember) }}">{{ $communityMember->name }}</a>
            </h2>
            <p>{{ $communityMember->locality }}, {{ get_region_name($communityMember->region, ["CA"], locale()) }}</p>
        </article>
        @empty
        <p>{{ __('No community members found.') }}</p>
        @endforelse
    </div>
</x-app-layout>
