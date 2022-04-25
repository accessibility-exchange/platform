<x-app-wide-layout>
    <x-slot name="title">{{ __('My dashboard') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">
            <small>{{ $currentUser->name }}@if($currentUser->regulatedOrganization()), {{ $currentUser->regulatedOrganization()->name }}@endif</small><br />
            {{ __('My dashboard') }}
        </h1>
        @if($currentUser->context == 'community-member')
        <p><strong>{{ __('Roles:') }}</strong> {{ implode(', ', $currentUser->communityMember->communityRoles()->pluck('name')->toArray()) }}. <a href="{{ localized_route('community-members.show-role-edit') }}">{{ __('Edit roles') }}</a></p>
        @endif
    </x-slot>

    @if($currentUser->context === 'community-member')
        @include('dashboard.community-member')
    @elseif ($currentUser->context === 'regulated-organization')
        @include('dashboard.regulated-organization')
    @endif

</x-app-wide-layout>
