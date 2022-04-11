<x-app-wide-layout>
    <x-slot name="title">{{ __('My dashboard') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">
            <small>{{ $currentUser->name }}@if($currentUser->regulatedOrganization()), {{ $currentUser->regulatedOrganization()->name }}@endif</small><br />
            {{ __('My dashboard') }}
        </h1>
    </x-slot>

    @if($currentUser->context === 'community-member')
        @include('dashboard.community-member')
    @elseif ($currentUser->context === 'regulated-organization')
        @include('dashboard.regulated-organization')
    @endif

</x-app-wide-layout>
