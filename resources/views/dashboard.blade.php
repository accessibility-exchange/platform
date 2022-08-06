<x-app-wide-layout>
    <x-slot name="title">{{ __('My dashboard') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">
            <small>{{ $currentUser->name }}@if($memberable), {{ $memberable->name }}@endif</small><br />
            {{ __('My dashboard') }}
        </h1>
        @if($currentUser->context == 'individual')
        <p><strong>{{ __('Roles:') }}</strong> {{ implode(', ', $currentUser->individual->individualRoles()->pluck('name')->toArray()) }}. <a href="{{ localized_route('individuals.show-role-edit') }}">{{ __('Edit roles') }}</a></p>
        @endif

        @if($currentUser->context == 'organization')
            <p><strong>{{ __('Roles:') }}</strong> {{ $currentUser->organization->organizationRoles->isEmpty() ? __('None selected') : implode(', ', $currentUser->organization->organizationRoles()->pluck('name')->toArray()) }}. <a href="{{ localized_route('organizations.show-role-edit', $currentUser->organization) }}">{{ __('Edit roles') }}</a></p>
        @endif
    </x-slot>

    @if($currentUser->context === 'individual')
        @include('dashboard.individual')
    @elseif($currentUser->context === 'organization')
        @include('dashboard.organization')
    @elseif ($currentUser->context === 'regulated-organization')
        @include('dashboard.regulated-organization')
    @endif

</x-app-wide-layout>
