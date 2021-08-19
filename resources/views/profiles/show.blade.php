<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ $profile->name }}
        </h1>
        <div class="meta">
            <p>{{ __('profile.role_individual_consultant') }}</p>
            <p>{{ $profile->locality }}, {{ get_region_name($profile->region, ["CA"], locale()) }}</p>
            @if($profile->pronouns)
            <p>{{ $profile->pronouns }}</p>
            @endif
        </div>
    </x-slot>

    <h2>{{ __('profile.about_person', ['name' => $profile->name]) }}</h2>

    {!! Illuminate\Mail\Markdown::parse($profile->bio) !!}

    @can('update', $profile)
    <p><a href="{{ localized_route('profiles.edit', $profile) }}">{{ __('profile.edit_profile') }}</a></p>
    @endcan
</x-app-layout>
