<x-app-wide-layout>
    <x-slot name="title">{{ __('My dashboard') }}</x-slot>
    <x-slot name="header">
        @if($invitationable)
            <x-invitation>
                <p class="flex items-center gap-4"><span class="bg-magenta-3 w-5 h-5 rounded-full"></span>{{ __('You have been invited to join :invitationable’s team.', ['invitationable' => $invitationable->name]) }}</p>
                <p><a class="cta secondary" href="{{ $acceptUrl }}">{{ __('Accept') }}</a></p>
            </x-invitation>
        @endif
        <p>{{ $user->name }}@if($memberable), {{ $memberable->name }}@endif</p>
        <h1 class="mt-0" itemprop="name">
            {{ __('My dashboard') }}
        </h1>
        @if($user->context == 'individual')
        <p><strong>{{ __('Roles:') }}</strong> {{ implode(', ', $user->individual->display_roles) }}. <a href="{{ localized_route('individuals.show-role-edit') }}">{{ __('Edit roles') }}</a></p>
        @endif

        @if($user->context == 'organization')
            <p><strong>{{ __('Roles:') }}</strong> {{ empty($user->organization->display_roles) ? __('None selected') : implode(', ', $user->organization->display_roles) }}. <a href="{{ localized_route('organizations.show-role-edit', $user->organization) }}">{{ __('Edit roles') }}</a></p>
        @endif
    </x-slot>

    @if($user->context === 'individual')
        @include('dashboard.individual')
    @elseif($user->context === 'organization')
        @include('dashboard.organization')
    @elseif ($user->context === 'regulated-organization')
        @include('dashboard.regulated-organization')
    @endif

</x-app-wide-layout>
