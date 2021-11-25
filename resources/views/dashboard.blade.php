<x-app-wide-layout>
    <x-slot name="title">{{ __('My dashboard') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">
            <small>{{ $currentUser->name }}@if($currentUser->entity()), {{ $currentUser->entity()->name }}@endif</small><br />
            {{ __('My dashboard') }}
        </h1>
    </x-slot>

    @if($currentUser->context === 'consultant')
        @include('dashboard.community-member')
    @elseif ($currentUser->context === 'entity')
        @include('dashboard.entity-representative')
    @endif

</x-app-wide-layout>
