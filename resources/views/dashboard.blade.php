<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('dashboard.title') }}</h1>
    </x-slot>

    <p>{{ __('dashboard.welcome', ['name' => Auth::user()->name]) }}</p>

    <h2>{{ __('user.consultant_profile_title') }}</h2>

    @if(Auth::user()->consultantProfile)
    <p>
        <a href="{{ localized_route('consultant-profiles.show', ['consultantProfile' => Auth::user()->consultantProfile]) }}"><strong>{{ Auth::user()->consultantProfile->name }}</strong></a><br />
        <a href="{{ localized_route('consultant-profiles.edit', ['consultantProfile' => Auth::user()->consultantProfile]) }}">{{ __('consultant-profile.edit_title') }}</a>
    </p>
    @else
    <p>{!! __('user.no_consultant_profile', ['link' => '<a href="' . localized_route('consultant-profiles.create') . '">' . __('user.create_consultant_profile') . '</a>']) !!}</p>
    @endif
</x-app-layout>
