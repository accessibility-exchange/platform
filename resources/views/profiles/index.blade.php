<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('profile.index_title') }}
        </h1>
    </x-slot>

   <div class="flow">
        @forelse($profiles as $profile)
        <article>
            <h2>
                <a href="{{ localized_route('profiles.show', $profile) }}">{{ $profile->name }}</a>
            </h2>
            <p>{{ $profile->locality }}, {{ get_region_name($profile->region, ["CA"], locale()) }}</p>
        </article>
        @empty
        <p>{{ __('profile.none_found') }}</p>
        @endforelse
    </div>
</x-app-layout>
