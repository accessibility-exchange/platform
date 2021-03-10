<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('dashboard.title') }}</h1>
    </x-slot>

    <p>{{ __('dashboard.welcome', ['name' => Auth::user()->name]) }}</p>

    <h2>{{ __('user.profile_title') }}</h2>

    @if(Auth::user()->profile)
    <p>
        <a href="{{ localized_route('profiles.show', ['profile' => Auth::user()->profile]) }}"><strong>{{ Auth::user()->profile->name }}</strong></a><br />
        <a href="{{ localized_route('profiles.edit', ['profile' => Auth::user()->profile]) }}">{{ __('profile.edit_link') }}</a>
    </p>
    @else
    <p>{!! __('user.no_profile', ['link' => '<a href="' . localized_route('profiles.create') . '">' . __('user.create_profile') . '</a>']) !!}</p>
    @endif

    <h2>{{ __('user.organizations_title') }}</h2>

    @if(!Auth::user()->organizations->isEmpty())
        @foreach(Auth::user()->organizations as $organization)
        <p>
            <a href="{{ localized_route('organizations.show', $organization) }}">{{ $organization->name }}</a><br />
            @if(Auth::user()->isAdministratorOf($organization))
            <a href="{{ localized_route('organizations.edit', $organization) }}">{{ __('organization.edit_title') }}</a>
            @endif
        </p>
        @endforeach
    @else
    <p>{!! __('user.no_organization', ['create_link' => '<a href="' . localized_route('organizations.create') . '">' . __('user.create_organization') . '</a>']) !!}</p>
    @endif
</x-app-layout>
